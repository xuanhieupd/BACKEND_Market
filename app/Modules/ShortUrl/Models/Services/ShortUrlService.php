<?php

namespace App\Modules\ShortUrl\Models\Services;

use App\Modules\ShortUrl\Exceptions\SpecificException;
use function Illuminate\Support\Facades\Event;

class ShortUrlService
{

    /**
     * @param $relativeUrl
     * @return string
     */
    public static function toUrl($appId, $relativeUrl)
    {
        return self::getBaseUrl($appId) . $relativeUrl;
    }

    public static function getBaseUrl($appId)
    {
        switch ($appId) {
            case 'B':
                return 'nhboxvnapp://';

            case 'M':
                return 'nhmarketvnapp://';
        }
    }


    public static function specific($modelId)
    {
        $appId = substr($modelId, 0, 1);
        $modelId = substr($modelId, 1);

        if (blank($appId) || blank($modelId)) {
            throw new SpecificException();
        }

        return array(
            'appId' => $appId,
            'modelId' => $modelId
        );
    }
}
