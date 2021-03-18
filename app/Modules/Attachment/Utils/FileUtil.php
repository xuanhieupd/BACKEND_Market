<?php

namespace App\Modules\Attachment\Utils;

use App\Modules\Attachment\FileWrapper;
use Illuminate\Support\Facades\Storage;

class FileUtil
{

    public static function putStream($path, FileWrapper $fileWrapper)
    {
        $fullPath = self::resolvePath($path);

        return $fileWrapper->getFile()->move(
            pathinfo($fullPath, PATHINFO_DIRNAME),
            pathinfo($fullPath, PATHINFO_BASENAME)
        );
    }

    public static function resolvePath($path)
    {
        list($prefix, $path) = self::getPrefixAndPath($path);
        return Storage::disk($prefix)->path($path);
    }

    /**
     * @param string $path
     * @return string[] [:prefix, :path]
     * @throws InvalidArgumentException
     *
     * @author xuanhieupd
     */
    public static function getPrefixAndPath($path)
    {
        if (strpos($path, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $path);
        }

        return explode('://', $path, 2);
    }

    public static function isVideoInlineDisplaySafe($extension, &$contentType = null)
    {
        if (!$extension) {
            return false;
        }

        $extension = strtolower($extension);
        $types = config('attachment.videoInlineDisplaySafe');

        if (!isset($types[$extension])) {
            return false;
        }

        $contentType = $types[$extension];

        return true;
    }
}
