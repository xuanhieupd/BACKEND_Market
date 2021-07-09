<?php
/**
 * Created by PhpStorm.
 * @author Julyboy <cntt0401.luuvietduc@gmail.com>
 * Date: 27/6/2021
 */

namespace App\Modules\Base\Helpers;


use App\Modules\Attachment\Models\Entities\Attachment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * @param $dirPath
     * @return bool
     * @copyright (c) 4:45 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public static function makeDirectory($dirPath) {
        if (file_exists($dirPath) && is_dir($dirPath)) {
            return true;
        }

        return File::makeDirectory($dirPath, $mode = 0777, true, true);
    }

    /**
     * @param $filePath
     * @copyright (c) 5:17 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public static function removeFile($filePath){

        $filesystemInstance = Storage::disk('cdn');

        if ($filesystemInstance->exists($filePath)) {
            $filesystemInstance->delete($filePath);
        }
    }

    /**
     * @param $fileInstance
     * @param string $baseDir
     * @return false|string
     * @copyright (c) 9:23 AM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public static function uploadFile($fileInstance, $baseDir = 'avatars'){
        $fileName = strtr(':time-:randomName.png', array(':time' => time(), ':randomName' => str_random(8)));
        $dirPath = base_path('uploads/' . $baseDir);

        $relativePathName = $baseDir. '/' . $fileName;
        $isUploaded = $fileInstance->move($dirPath, $fileName);
        self::makeDirectory($dirPath);
        if (!$isUploaded) {
            return false;
        }
        return $relativePathName;
    }

    /**
     * @param $filePath
     * @param string $storage
     * @param false $defaultImage
     * @return string
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     * @copyright (c) 29/6/2021
     */
    public static function getFileUrl($filePath, $storage = 'cdn', $defaultImage = false){
        $filesystemInstance = Storage::disk($storage);

//        if (!$filesystemInstance->exists($filePath) || !is_file($filesystemInstance->path($filePath))) {
//            return $defaultImage ? $defaultImage : Attachment::getDefaultImageAttribute();
//        }

        return $defaultImage ?: $filesystemInstance->url($filePath) ;
    }
}
