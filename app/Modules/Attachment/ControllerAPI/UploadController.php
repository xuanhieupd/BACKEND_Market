<?php

namespace App\Modules\Attachment\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Attachment\Requests\UploadRequest;
use App\Modules\Attachment\Resources\AttachmentResource;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadController extends AbstractController
{

    /**
     * @var AttachmentRepository
     */
    private $attachmentRepo;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param AttachmentInterface $attachmentRepo
     * @author xuanhieupd
     */
    public function __construct(AttachmentInterface $attachmentRepo)
    {
        $this->filesystem = Storage::disk($this->getDiskName());
        $this->attachmentRepo = $attachmentRepo;
    }

    /**
     * Upload file
     *
     * @param UploadRequest $request
     * @return mixed
     * @throws \Throwable
     * @author xuanhieupd
     */
    public function actionIndex(UploadRequest $request)
    {
        $visitor = Auth::user();
        $fileInfo = $request->getFileUpload();

        $strDir = $this->getDir($request);
        $fileHash = md5_file($fileInfo->getRealPath());
        $filename = strtolower(implode('.', array($fileHash, $fileInfo->getClientOriginalExtension())));

        DB::beginTransaction();
        try {
            $attachmentInfo = $this->attachmentRepo->create(array(
                'store_id' => $visitor->getAttribute('store_id'),
                'user_id' => auth()->id(),
                'path' => $strDir,
                'file_name' => $filename,
                'mime_type' => $fileInfo->getMimeType(),
                'size' => $fileInfo->getSize(),
                'disk' => $this->getDiskName(),
                'cdn_name' => '', 'collection_name' => '',
            ));

            $isUploaded = $fileInfo->move($this->filesystem->path($strDir), $filename);
            throw_if(!$isUploaded, new \Exception('Không chuyển được file'));

            DB::commit();
            return new AttachmentResource($attachmentInfo);
        } catch (FileException $fileException) {
            info('FileException' . $fileException);
            DB::rollBack();
            return $this->responseError('FileException: ' . $fileException->getMessage());
        } catch (\Exception $exception) {
            info($exception);
            DB::rollBack();
            return $this->responseError('Upload thất bại');
        }
    }

    /**
     * Lấy path thư mục sẽ lưu trữ
     *
     * @param UploadRequest $uploadRequest
     * @return string
     * @author xuanhieupd
     */
    protected function getDir(UploadRequest $uploadRequest)
    {
        $fileInfo = $uploadRequest->getFileUpload();

        $strDir = implode('/', array(
            $uploadRequest->getHandlerId(),
            date('dmy'),
            !blank($fileInfo->getMimeType()) ? substr($fileInfo->getMimeType(), 0, strpos($fileInfo->getMimeType(), '/')) : 'unknown',
        ));

        if (!$this->filesystem->exists($strDir) || !File::isDirectory($this->filesystem->path($strDir))) {
            $this->filesystem->makeDirectory($strDir);
        }

        return $strDir;
    }

    /**
     * @return string
     */
    protected function getDiskName()
    {
        return 'market-cdn';
    }
}
