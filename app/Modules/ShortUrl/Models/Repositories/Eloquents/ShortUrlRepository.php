<?php

namespace App\Modules\ShortUrl\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\ShortUrl\Models\Entities\ShortUrl;
use App\Modules\ShortUrl\Models\Repositories\Contracts\ShortUrlInterface;

class ShortUrlRepository extends AbstractRepository implements ShortUrlInterface
{

    /**
     * @param $code
     * @return ShortUrl
     */
    public function getShortUrlByCode($code)
    {
        return $this->makeModel()
            ->where('code', $code)
            ->first();
    }

    public function model()
    {
        return ShortUrl::class;
    }

}
