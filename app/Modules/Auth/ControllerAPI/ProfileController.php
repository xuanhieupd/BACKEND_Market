<?php

namespace App\Modules\Auth\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Auth\Requests\ProfileRequest;
use App\Modules\Auth\Resources\ProfileResource;
use App\Modules\Notification\Models\Entities\Token;
use App\Modules\Notification\Models\Repositories\Contracts\TokenInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends AbstractController
{

    /**
     * @var TokenInterface
     */
    protected $tokenRepo;

    /**
     * Constructor.
     *
     * @param TokenInterface $tokenRepo
     * @author xuanhieupd
     */
    public function __construct(TokenInterface $tokenRepo)
    {
        $this->tokenRepo = $tokenRepo;
    }

    /**
     * Thông tin cá nhân
     *
     * @param ProfileRequest $request
     * @return ProfileResource
     * @author xuanhieupd
     */
    public function actionIndex(ProfileRequest $request)
    {
        $visitor = Auth::user();

        !blank($request->getPlayerId()) ? $this->saveToken($request) : null;
        return new ProfileResource($visitor);
    }

    /**
     * @param ProfileRequest $request
     */
    protected function saveToken(ProfileRequest $request)
    {
        $visitor = Auth::user();

        $tokenInfo = new Token(array(
            'store_id' => $visitor->getStoreId(),
            'user_id' => $visitor->getId(),
            'type_id' => $request->getDeviceType(),
            'device_id' => $request->getDeviceId(),
            'token_value' => $request->getPlayerId(),
        ));

        $this->tokenRepo->store($tokenInfo);
    }


}
