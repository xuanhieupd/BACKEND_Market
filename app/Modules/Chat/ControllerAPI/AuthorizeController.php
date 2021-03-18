<?php

namespace App\Modules\Chat\ControllerAPI;

use App\Base\AbstractController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Pusher\Facades\Pusher;

class AuthorizeController extends AbstractController
{

    /**
     * Xác thực socket
     *
     * @param Request $request
     * @return string
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $channelName = $request->get('channel_name');
        $socketId = $request->get('socket_id');

        $visitor = Auth::user();
        return Pusher::presence_auth($channelName, $socketId, $visitor->getId(), array());
    }

}
