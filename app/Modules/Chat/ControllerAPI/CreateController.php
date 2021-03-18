<?php

namespace App\Modules\Chat\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Chat\Exceptions\ParticipantException;
use App\Modules\Chat\Resources\ConversationResource;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Chat\Facades\ChatFacade as Chat;

class CreateController extends AbstractController
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * Tạo cuộc hội thoại
     *
     * @param Request $request
     * @return ConversationResource
     * @throws \Throwable
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        try {
            $this->request = $request;

            $from = $this->from();
            $to = $this->to();

            $conversationInfo = Chat::conversations()->between($from, $to);
            $conversationInfo = $conversationInfo ?? Chat::createConversation(array($from, $to));

            return new ConversationResource($conversationInfo);
        } catch (ParticipantException $participantException) {
            return $this->responseError('ParticipantException');
        } catch (\Exception $e) {
            return $this->responseError('Không tạo được cuộc hội thoại');
        }
    }

    /**
     * @return Authenticatable
     */
    protected function from()
    {
        return Auth::user();
    }

    /**
     * @return Store
     * @throws \Throwable
     */
    protected function to()
    {
        $storeIdFromInput = $this->request->get('id');

        $storeRepo = app(StoreInterface::class);
        $storeInfo = $storeRepo->getStoreById($storeIdFromInput)->first();

        throw_if(!$storeInfo, new ParticipantException());
        return $storeInfo;
    }

}
