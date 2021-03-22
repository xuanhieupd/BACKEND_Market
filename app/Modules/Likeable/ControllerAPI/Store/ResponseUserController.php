<?php

namespace App\Modules\Likeable\ControllerAPI\Store;

use App\Base\AbstractController;
use App\Modules\Likeable\Models\Entities\Like;
use App\Modules\Likeable\Models\Repositories\Contracts\LikeInterface;
use App\Modules\Likeable\Models\Repositories\LikeRepository;
use App\Modules\Likeable\Requests\CancelUserRequest;
use App\Modules\Likeable\Requests\ResponseUserRequest;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
use App\Modules\User\Models\Entities\User;
use Illuminate\Support\Facades\Auth;

class ResponseUserController extends AbstractController
{

    /**
     * @var LikeRepository
     */
    protected $likeRepo;

    /**
     * @var SettingUserRepository
     */
    protected $settingUserRepo;

    /**
     * Constructor.
     *
     * @param LikeInterface $likeRepo
     * @param SettingUserInterface $settingUserRepo
     * @author xuanhieupd
     */
    public function __construct(LikeInterface $likeRepo, SettingUserInterface $settingUserRepo)
    {
        $this->likeRepo = $likeRepo;
        $this->settingUserRepo = $settingUserRepo;
    }

    /**
     * Phàn hồi người dùng
     *
     * @param ResponseUserRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(ResponseUserRequest $request)
    {
        $visitor = Auth::user();

        $likes = $this->likeRepo->getUsersLikeStore($visitor->getStoreId())
            ->whereIn('id', $request->getLikeIds())
            ->where('author_type', User::class)
            ->get();

        Like::whereIn('id', $likes->pluck('id')->toArray())->update(array('status' => STATUS_ACTIVE));

        $settings = $this->settingUserRepo->makeModel()
            ->where('store_id', $visitor->getStoreId())
            ->whereIn('user_id', $likes->pluck('user_id')->toArray())
            ->get();

        $insertResults = collect();
        foreach ($likes as $likeInfo) {
            $settingInfo = $settings->where('user_id', $likeInfo->getAttribute('author_id'))->first();

            if ($settingInfo) {
                $settingInfo->setAttribute('display_id', $request->getDisplayId());
                $settingInfo->save();

                continue;
            }

            $insertResults->push(array(
                'store_id' => $visitor->getStoreId(),
                'user_id' => $likeInfo->getAttribute('author_id'),
                'customer_id' => null,
                'display_id' => $request->getDisplayId(),
                'alias_name' => '',
            ));
        }

        $this->settingUserRepo->insert($insertResults->toArray());
        return $this->responseMessage('Thành công');
    }

    /**
     * Hủy
     *
     * @param CancelUserRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionCancel(CancelUserRequest $request)
    {
        $visitor = Auth::user();

        $this->likeRepo->getUsersLikeStore($visitor->getStoreId())
            ->whereIn('id', $request->getLikeIds())
            ->delete();

        return $this->responseMessage('Thành công');
    }

}
