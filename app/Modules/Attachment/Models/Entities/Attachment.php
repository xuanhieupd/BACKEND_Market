<?php

/**
 * Attachment Model
 *
 * @author xuanhieupd
 * @package Attachment
 * @copyright (c) 07.10.2020, HNW
 */

namespace App\Modules\Attachment\Models\Entities;

use App\Base\AbstractModel;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachment extends AbstractModel
{

    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_FILE = 'file';

    protected $filesystem;
    protected $connection = 'box';
    protected $table = 'hnw_attachment';
    protected $primaryKey = 'attachment_id';
    public static $tableAlias = 'hnw_attachment';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'user_id',
        'cdn_name',
        'collection_name',
        'path',
        'file_name',
        'mime_type',
        'disk',
        'size',
        'sort_order',
    );

    protected $casts = array();

    /**
     * Alias for `attachment_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('attachment_id');
    }

    /**
     * Type of Attachment
     *
     * @return string
     * @author xuanhieupd
     */
    public function getType()
    {
        $mime = $this->getAttribute('mime_type');
        return substr($mime, 0, strpos($mime, '/'));
    }

    /**
     * Link URL
     *
     * @return string
     * @author xuanhieupd
     */
    public function getLinkUrl()
    {
        $filePath = implode('/', array(
            $this->getAttribute('path'),
            $this->getAttribute('file_name'),
        ));

        return $this->getFilesystem()->url($filePath);
    }

    /**
     * Ảnh thumbnail
     *
     * @return string
     * @author xuanhieupd
     */
    public function getThumbnailUrl()
    {
        $dataInfo = $this->attachmentData;
        return $dataInfo ? $dataInfo->getThumbnailUrl() : '';
    }

    /**
     * Hình ảnh mặc định
     *
     * @return string
     * @author xuanhieupd
     */
    public function getDefaultImageAttribute()
    {
        return Storage::disk('cdn')->url('no-image.png');
    }

    /**
     * Kiểm tra file có tồn tại hay không ?
     *
     * @param string $fileName
     * @return boolean
     * @author xuanhieupd
     */
    protected function _checkExists($fileName, $filesystemInstance = null)
    {
        return true;
        $filesystemInstance = is_null($filesystemInstance) ? Storage::disk('cdn') : $filesystemInstance;

        return $filesystemInstance->exists($fileName) && is_file($filesystemInstance->path($fileName));
    }

    /**
     * Định dạng file
     *
     * @author xuanhieupd
     */
    public function getFileTypeAttribute()
    {
        $typeId = $this->getAttribute('mime_type');

        if (Str::startsWith($typeId, 'image')) {
            return self::TYPE_IMAGE;
        }

        if (Str::startsWith($typeId, 'video')) {
            return self::TYPE_VIDEO;
        }

        if (Str::startsWith($typeId, 'audio')) {
            return self::TYPE_AUDIO;
        }

        return self::TYPE_FILE;
    }

    /**
     * Lấy ảnh thumbnail
     *
     * @return string
     * @author xuanhieupd
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->getFileTypeAttribute() !== self::TYPE_IMAGE) return null;

        $filesystemInstance = Storage::disk('cdn');
        $fileName = $this->getAttribute('file_name');

        /* Không tìm thấy ảnh gốc */
        if (!$this->_checkExists($fileName)) return $this->getDefaultImageAttribute();

        $filesystemThumbnailInstance = Storage::disk('thumbnail');
        $justFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $justExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $sizeIds = $this->getImageSizeKeepAspectRatio($filesystemInstance->path($fileName), 300, 400);
        list($width, $height) = array($sizeIds['width'], $sizeIds['height']);

        $justFileName .= '-' . implode('x', $sizeIds);
        $thumbnailName = $justFileName . '.' . $justExtension;

        return ($this->_checkExists($thumbnailName, $filesystemThumbnailInstance)) ?
            $filesystemInstance->url($fileName) :
            $this->getFullUrlAttribute();
    }

    /**
     * Render đường dẫn file
     *
     * @return string
     * @author xuanhieupd
     */
    public function getFullUrlAttribute()
    {
        $filesystemInstance = Storage::disk('cdn');
        $cdnName = $this->getAttribute('cdn_name');
        $fileName = $this->getAttribute('file_name');

        return $cdnName != 'GOOGLE' ?
            $filesystemInstance->url($fileName) :
            'https://storage.googleapis.com/kingpostervn.appspot.com/NHBox/' . $fileName;
    }

    /**
     * Lấy kích thước ảnh sau khi giảm tỉ lệ xuống
     *
     * @param type $imageUrl
     * @param type $maxWidth
     * @param type $maxHeight
     * @return type
     * @author xuanhieupd
     */
    public function getImageSizeKeepAspectRatio($imageUrl, $maxWidth, $maxHeight)
    {
        try {
            $imageDimensions = getimagesize($imageUrl);
        } catch (\Exception $ex) {
            $imageDimensions = array();
        }

        if (count($imageDimensions) > 2) {
            $imageWidth = $imageDimensions[0];
            $imageHeight = $imageDimensions[1];
        } else {
            $imageWidth = $maxWidth;
            $imageHeight = $maxHeight;
        }

        $imageSize['width'] = $imageWidth;
        $imageSize['height'] = $imageHeight;

        if ($imageWidth > $maxWidth || $imageHeight > $maxHeight) {
            if ($imageWidth > $imageHeight) {
                $imageSize['height'] = floor(($imageHeight / $imageWidth) * $maxWidth);
                $imageSize['width'] = $maxWidth;
            } else {
                $imageSize['width'] = floor(($imageWidth / $imageHeight) * $maxHeight);
                $imageSize['height'] = $maxHeight;
            }
        }

        return (count($imageSize) != 2) ? array('width' => $maxWidth, 'height' => $maxHeight) : $imageSize;
    }

    /**
     * Danh sách loại được phép upload
     *
     * @return string[]
     * @author xuanhieupd
     */
    public static function getHandlerIds()
    {
        return array(
            'product',
            'message',
            'feed',
        );
    }

    /**
     * @return Filesystem
     * @author xuanhieupd
     */
    protected function getFilesystem()
    {
        if (!$this->filesystem) {
            $diskName = $this->getAttribute('disk');
            $diskName = blank($diskName) ? 'cdn' : $diskName;

            try {
                $this->filesystem = Storage::disk($diskName);
            } catch (\Exception $e) {
                $this->filesystem = Storage::disk('cdn');
            }
        }

        return $this->filesystem;
    }


}
