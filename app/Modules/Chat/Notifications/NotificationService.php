<?php

namespace App\Modules\Chat\Notifications;

use App\Libraries\Chat\Models\Message;
use App\Libraries\Chat\Models\Participation;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Notification\Models\Repositories\Contracts\TokenInterface;
use App\Modules\Store\Models\Entities\Store;
use Illuminate\Support\Collection;
use Ixudra\Curl\Facades\Curl;

class NotificationService
{

    /**
     * Send notification
     *
     * @param Participation $participationInfo
     * @param Message $messageInfo
     */
    public function send(Participation $participationInfo, Message $messageInfo)
    {
        $dynamicId = $participationInfo->getAttribute('messageable_id');
        $tokens = ($participationInfo->getAttribute('messageable_type') === Store::class) ?
            $this->getTokensStore($dynamicId) :
            $this->getTokensUser($dynamicId);

        $appInfo = ($participationInfo->getAttribute('messageable_type') === Store::class) ?
            $this->getMarketOnesignal() :
            $this->getBoxOnesignal();

        $withDatas = array(
            'app_id' => $appInfo['app_id'],
            'include_player_ids' => $this->validTokens(CollectionHelper::pluckUnique($tokens, 'token_value')->toArray()),
            'contents' => array(
                'en' => $messageInfo->getMessageOverview()
            ),
            'headings' => array(
                'en' => $participationInfo->messageable ? $participationInfo->messageable->getFullName() : 'NoName',
            )
        );

        $dataRes = Curl::to('https://onesignal.com/api/v1/notifications')
            ->withHeader('Authorization', $appInfo['secret'])
            ->withData($withDatas)
            ->asJson(true)
            ->post();

        info($dataRes);
    }

    protected function validTokens($tokens)
    {
        $tokenResults = [];

        foreach ($tokens as $tokenValue) {
            $isValid = preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $tokenValue);
            if (!$isValid) continue;

            $tokenResults[] = $tokenValue;
        }

        return $tokenResults;
    }

    /**
     * @param $userId
     * @return Collection
     */
    protected function getTokensUser($userId)
    {
        return $this->_getTokenModel()->makeModel()->where('user_id', $userId)->get();
    }

    /**
     * @param $storeId
     * @return Collection
     */
    protected function getTokensStore($storeId)
    {
        return $this->_getTokenModel()->makeModel()->where('store_id', $storeId)->get();
    }

    /**
     * @return TokenInterface
     */
    protected function _getTokenModel()
    {
        return app(TokenInterface::class);
    }

    protected function getBoxOnesignal()
    {
        return array(
            'app_id' => '4c5f071b-9a7e-469d-b623-020705d0dea1',
            'secret' => 'NTJmNDQxYWEtMGU0Ny00YTFmLTlkZGQtODRjZmU0NDMyNWIw',
        );
    }

    protected function getMarketOnesignal()
    {
        return array(
            'app_id' => 'eeb8c897-fbd3-443a-ae6c-aa39d3a29d92',
            'secret' => 'NGE5ZDgyMTYtNjdiNy00ZGViLTkxN2EtNTdhY2NlNWJlZTQ0',
        );
    }
}
