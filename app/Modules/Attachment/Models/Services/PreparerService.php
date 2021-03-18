<?php

namespace App\Modules\Attachment\Models\Services;

use App\Base\AbstractService;
use App\Modules\Attachment\AbstractHandler;
use App\Modules\Attachment\FileWrapper;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Attachment\Models\Entities\AttachmentData;
use App\Modules\Attachment\Utils\FileUtil;
use App\Modules\User\Models\Entities\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PreparerService extends AbstractService
{

    /**
     * @param AbstractHandler $handler
     * @param FileWrapper $file
     * @param User $user
     * @param $hash
     * @return Attachment
     * @throws Exception
     */
    public function insertAttachment(AbstractHandler $handler, FileWrapper $file, User $user, $hash)
    {
        $extra = [];

        $extension = $file->getExtension();
        if (FileUtil::isVideoInlineDisplaySafe($extension)) {
            $extra['file_path'] = 'data://video/%FLOOR%/%DATA_ID%-%HASH%.mp4';
        }

        $handler->beforeNewAttachment($file, $extra);

        $data = $this->insertDataFromFile($file, $user->getId(), $extra);
        return $this->insertTemporaryAttachment($handler, $data, $hash, $file);
    }

    /**
     * @param FileWrapper $file
     * @param $userId
     * @param array $extra
     * @return AttachmentData
     * @throws Exception
     */
    public function insertDataFromFile(FileWrapper $file, $userId, array $extra = [])
    {
        $data = $this->setupDataInsertFromFile($file, $userId, $extra);

        $thumbnailImage = $this->generateAttachmentThumbnail($file);
        if ($thumbnailImage) {
            $data->setAttribute('thumbnail_width', $thumbnailImage->getWidth());
            $data->setAttribute('thumbnail_height', $thumbnailImage->getHeight());
        }

        DB::beginTransaction();
        $data->save();

        $dataPath = $this->makeDirIfNotExists($data->getAbstractedDataPath());
        $thumbnailPath = $this->makeDirIfNotExists($data->getAbstractedThumbnailPath());

        try {
            FileUtil::putStream($dataPath, $file);
            $thumbnailImage ? $thumbnailImage->save(FileUtil::resolvePath($thumbnailPath)) : null;
        } catch (Exception $e) {
            DB::rollBack();

            @unlink(FileUtil::resolvePath($dataPath));
            $thumbnailImage ? $thumbnailImage->destroy() : null;

            throw $e;
        }

        DB::commit();

        return $data;
    }

    /**
     * @param FileWrapper $file
     * @param int $userId
     * @param array $extra
     * @return AttachmentData
     * @author xuanhieupd
     */
    protected function setupDataInsertFromFile(FileWrapper $file, int $userId, array $extra = [])
    {
        $extra = array_replace(array(
            'file_path' => '',
            'upload_date' => null,
        ), $extra);

        return new AttachmentData(array(
            'user_id' => $userId,
            'upload_date' => $extra['upload_date'] ? $extra['upload_date'] : now()->timestamp,
            'filename' => $file->getFileName(),
            'file_size' => $file->getFile()->getSize(),
            'file_hash' => md5($file->getFilePath()),
            'file_path' => $extra['file_path'],
            'width' => $file->getImageWidth(),
            'height' => $file->getImageHeight(),
        ));
    }

    public function updateDataFromFile(AttachmentData $data, FileWrapper $file, array $extra = [])
    {
        $this->setupDataUpdateFromFile($data, $file, $extra);
        if (!$data->preSave()) {
            throw new \XF\PrintableException($data->getErrors());
        }

        $sourceFile = $file->getFilePath();
        $width = $data->width;
        $height = $data->height;

        $tempThumbFile = false;
        if ($data->isChanged('file_hash')) {
            if ($width && $height && $this->canResize($width, $height)) {
                $tempThumbFile = $this->generateAttachmentThumbnail($sourceFile, $thumbWidth, $thumbHeight);
                if ($tempThumbFile) {
                    $data->set('thumbnail_width', $thumbWidth, ['forceSet' => true]);
                    $data->set('thumbnail_height', $thumbHeight, ['forceSet' => true]);
                }
            }
        }

        $this->db()->beginTransaction();

        $previousDataPath = null;
        $previousThumbnailPath = null;

        $fileIsChanged = $data->isChanged(['file_hash', 'file_path']);
        if ($fileIsChanged) {
            $previousDataPath = $data->getExistingAbstractedDataPath();
            $previousThumbnailPath = $data->getExistingAbstractedThumbnailPath();
        }

        //$data->saveIfChanged($dataChanged, true, false);

//        if ($fileIsChanged && $dataChanged) {
//            $dataPath = $data->getAbstractedDataPath();
//            $thumbnailPath = $data->getAbstractedThumbnailPath();
//
//            try {
//                File::copyFileToAbstractedPath($sourceFile, $dataPath);
//
//                if ($tempThumbFile) {
//                    File::copyFileToAbstractedPath($tempThumbFile, $thumbnailPath);
//                }
//            } catch (\Exception $e) {
//                $this->db()->rollback();
//                $this->app->em()->detachEntity($data);
//
//                throw $e;
//            }
//
//            File::deleteFromAbstractedPath($previousDataPath);
//            File::deleteFromAbstractedPath($previousThumbnailPath);
//        }

        $this->db()->commit();

        return $data;
    }

    /**
     * @param AttachmentData $data
     * @param FileWrapper $file
     * @param array $extra
     */
    protected function setupDataUpdateFromFile(AttachmentData $data, FileWrapper $file, array $extra = [])
    {
        $data->setAttribute('file_size', $file->getFile()->getSize());
        $data->setAttribute('file_hash', md5_file($file->getFilePath()));
        $data->setAttribute('width', $file->getImageWidth());
        $data->setAttribute('height', $file->getImageHeight());

        if (isset($extra['file_path'])) {
            $data->setAttribute('file_path', $extra['file_path']);
        }
    }

    public function insertTemporaryAttachment(AbstractHandler $handler, AttachmentData $data, $tempHash, FileWrapper $file)
    {
        $attachment = new Attachment(array(
            'data_id' => $data->getId(),
            'content_type' => $handler->getContentType(),
            'temp_hash' => $tempHash,
            'user_id' => auth()->id(),
            'content_id' => 0,
            'unassociated' => 1,
            'view_count' => 0,
        ));

        $attachment->save();

        $handler->onNewAttachment($attachment, $file);

        return $attachment;
    }

    public function associateAttachmentsWithContent($tempHash, $contentType, $contentId)
    {
        $associated = 0;

        $attachmentFinder = $this->finder('XF:Attachment')->where('temp_hash', $tempHash);
        $attachments = $attachmentFinder->fetch();

        foreach ($attachments as $attachment) {
            $attachment->content_type = $contentType;
            $attachment->content_id = $contentId;
            $attachment->temp_hash = '';
            $attachment->unassociated = 0;

            $attachment->save();

            $container = $attachment->getContainer();
            $attachment->getHandler()->onAssociation($attachment, $container);

            $associated++;
        }

        return $associated;
    }

    /**
     * Tạo ảnh thumbnail
     *
     * @param FileWrapper $fileWrapper
     * @param $savePath
     * @return \Intervention\Image\Image|null
     * @author xuanhieupd
     */
    public function generateAttachmentThumbnail(FileWrapper $fileWrapper)
    {
        $width = $fileWrapper->getImageWidth();
        $height = $fileWrapper->getImageHeight();

        if (!$width || !$height || !$this->canResize($width, $height)) {
            return null;
        }

        $interventionImage = Image::make($fileWrapper->getFilePath());
        if (!$interventionImage) {
            return null;
        }

        $thumbSize = config('attachment.attachmentThumbnailDimensions');
        return $interventionImage->resize($thumbSize, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    /**
     * Tạo mới thư mục nếu không tồn tại
     *
     * @param $path
     * @return string
     * @author xuanhieupd
     */
    protected function makeDirIfNotExists($path)
    {
        File::makeDirectory(pathinfo($path, PATHINFO_DIRNAME), 0755, true, true);

        return $path;
    }

    /**
     * Can Resize
     *
     * @param $width
     * @param $height
     * @return bool
     * @author xuanhieupd
     */
    public function canResize($width, $height)
    {
        $maxResizePixels = config('attachment.maxImageResizePixelCount');
        if (!$maxResizePixels === null) {
            return true;
        }

        $total = $width * $height;
        return ($total <= $maxResizePixels);
    }

}
