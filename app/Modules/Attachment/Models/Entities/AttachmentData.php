<?php


namespace App\Modules\Attachment\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Attachment\Utils\FileUtil;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class AttachmentData extends AbstractModel
{

    protected $table = 'hnw_attachment_data';
    protected $primaryKey = 'data_id';
    public $timestamps = false;
    public static $tableAlias = 'hnw_attachment_data';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'user_id',
        'upload_date',
        'filename',
        'file_size',
        'file_hash',
        'file_path',
        'width',
        'height',
        'thumbnail_width',
        'thumbnail_height',
        'attach_count',
    );

    protected $casts = array();

    /**
     * Alias for `data_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('data_id');
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return strtolower(pathinfo($this->getAttribute('filename'), PATHINFO_EXTENSION));
    }

    public function getAbstractedDataPath()
    {
        return $this->_getAbstractedDataPath(
            $this->getId(),
            $this->getAttribute('file_path'),
            $this->getAttribute('file_hash')
        );
    }

    public function getExistingAbstractedDataPath()
    {
        return $this->_getAbstractedDataPath(
            $this->getAttribute('data_id'),
            $this->getAttribute('file_path'),
            $this->getAttribute('file_hash')
        );
    }

    protected function _getAbstractedDataPath($dataId, $filePath, $fileHash)
    {
        $group = floor($dataId / 1000);

        if ($filePath) {
            $placeholders = [
                '%INTERNAL%' => 'internal-data://', // for legacy
                '%DATA%' => 'data://', // for legacy
                '%DATA_ID%' => $dataId,
                '%FLOOR%' => $group,
                '%HASH%' => $fileHash
            ];
            $path = strtr($filePath, $placeholders);
            $path = str_replace(':///', '://', $path); // writing %INTERNAL%/path would cause this

            return $path;
        }

        return sprintf('internal-data://attachments/%d/%d-%s.data', $group, $dataId, $fileHash);

    }

    public function getAbstractedThumbnailPath()
    {
        return $this->_getAbstractedThumbnailPath(
            $this->getId(),
            $this->getAttribute('file_hash')
        );
    }

    public function getExistingAbstractedThumbnailPath()
    {
        return $this->_getAbstractedThumbnailPath(
            $this->getExistingValue('data_id'),
            $this->getExistingValue('file_hash')
        );
    }

    protected function _getAbstractedThumbnailPath($dataId, $fileHash)
    {
        return sprintf('data://attachments/%d/%d-%s.jpg', floor($dataId / 1000), $dataId, $fileHash);
    }

    /**
     * Ảnh thumbnail
     *
     * @return string|null
     * @author xuanhieupd
     */
    public function getThumbnailUrl()
    {
        if (!$this->getAttribute('thumbnail_width')) {
            return null;
        }

        $path = sprintf('attachments/%d/%d-%s.jpg', floor($this->getId() / 1000), $this->getId(), $this->getAttribute('file_hash'));
        return Storage::disk('data')->url($path);
    }

    /**
     * @return bool
     */
    public function hasThumbnail()
    {
        return $this->getAttribute('thumbnail_width') ? true : false;
    }

    public function isVideo()
    {
        if (!$this->getAttribute('file_path')) {
            return false;
        }

        $extension = strtolower($this->getExtension());
        return FileUtil::isVideoInlineDisplaySafe($extension);
    }

    public function getVideoUrl($canonical = false)
    {
        if (!$this->isVideo()) {
            return null;
        }

        $path = sprintf("video/%d/%d-%s.mp4", floor($this->getId() / 1000), $this->getId(), $this->getAttribute('file_hash'));
        return Storage::disk('data')->url($path);
    }

    /**
     * File có tồn tại hay không ?
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isDataAvailable()
    {
        $filePath = FileUtil::resolvePath($this->getAbstractedDataPath());
        return $filePath && file_exists($filePath);
    }

    protected function verifyFilePath(&$path)
    {
        if (!strlen($path)) {
            return true;
        }

        $placeholders = [
            '%INTERNAL%' => 'internal-data://', // for legacy
            '%DATA%' => 'data://', // for legacy
        ];
        $path = strtr($path, $placeholders);

        if (!preg_match('#^[a-z0-9-]+://#i', $path)) {
            throw new \LogicException("Invalid file path. Must be an abstracted path.");
        }

        return true;
    }

    protected function verifyFileName(&$fileName)
    {
        $maxLength = 100; // must match value in structure

        if (utf8_strlen($fileName) > $maxLength && $info = @pathinfo($fileName)) {
            if (!empty($info['extension'])) {
                $extension = '...' . $info['extension'];
            } else {
                $extension = '';
            }

            $fileName = utf8_substr($info['filename'], 0, $maxLength - utf8_strlen($extension)) . $extension;
        }

        return true;
    }
}
