<?php

namespace App\Modules\Chat\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Chat\Resources\ParticipantResource;
use App\Modules\User\Models\Entities\User;
use Illuminate\Http\Request;
use App\Libraries\Chat\Facades\ChatFacade as Chat;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends AbstractController
{

    /**
     * Danh sách cuộc hội thoại
     *
     * @param Request $request
     * @return ParticipantResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $paginationParams = array(
            'perPage' => 10,
            'page' => $request->get('page'),
        );

        $conversations = Chat::conversations()
            ->setPaginationParams($paginationParams)
            ->setParticipant($this->getAuthor($request))
            ->get();

        return ParticipantResource::collection($conversations);
    }

    /**
     * @return User
     */
    protected function getAuthor()
    {
        return Auth::user();
    }

}
