<?php

namespace App\Modules\Notification\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Notification\Models\Entities\Token;
use App\Modules\Notification\Models\Repositories\Contracts\TokenInterface;

class TokenRepository extends AbstractRepository implements TokenInterface
{

    /**
     * @param Token $tokenInfo
     * @return Token|false
     */
    public function store(Token $tokenInfo)
    {
        $tokenDb = $this->getTokenByDeviceId($tokenInfo->getAttribute('device_id'));
        $tokenDb = $tokenDb ? $tokenDb : new Token();

        $tokenDb->fill($tokenInfo->getAttributes());

        return $tokenDb->save() ? $tokenDb : false;
    }

    /**
     * @param $deviceId
     * @return Token
     */
    protected function getTokenByDeviceId($deviceId)
    {
        return $this->makeModel()->where('device_id', $deviceId)->first();
    }


    /**
     * @return Token
     */
    public function model()
    {
        return Token::class;
    }

}
