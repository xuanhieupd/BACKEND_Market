<?php


return array(
    'attachmentMaxFileSize' => 1024,
    'maxImageResizePixelCount' => 20000000,
    'attachmentThumbnailDimensions' => 150,
    'attachmentExtensions' => array(
        'zip',
        'txt',
        'pdf',
        'png',
        'jpg',
        'jpeg',
        'jpe',
        'gif',
        'mp4',
    ),
    'videoInlineDisplaySafe' => array(
        'm4v' => 'video/mp4',
        'mov' => 'video/quicktime',
        'mp4' => 'video/mp4',
        'mp4v' => 'video/mp4',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'ogv' => 'video/ogg',
        'webm' => 'video/webm',
    ),
    'attachmentMaxDimensions' => array(
        'width' => null,
        'height' => null,
    )
);
