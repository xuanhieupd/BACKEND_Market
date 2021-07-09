<?php

namespace App\Modules\ShortUrl\Models\Repositories\Contracts;

use App\Modules\ShortUrl\Models\Repositories\Eloquents\ShortUrlRepository;

/**
 * @see ShortUrlRepository
 */
interface ShortUrlInterface
{

    public function getShortUrlByCode($code);

}
