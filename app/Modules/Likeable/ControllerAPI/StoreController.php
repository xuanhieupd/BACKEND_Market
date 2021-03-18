<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use App\GlobalConstants;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Contracts\SettingUserInterface;
use App\Modules\Store\Modules\SettingUser\Models\Repositories\Eloquents\SettingUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends AbstractController
{

    /**
     * @var SettingUserRepository
     */
    protected $settingUserRepo;

    /**
     * Constructor.
     *
     * @param SettingUserInterface $settingUserRepo
     * @author xuanhieupd
     */
    public function __construct(SettingUserInterface $settingUserRepo)
    {
        $this->settingUserRepo = $settingUserRepo;
    }

    /**
     * Thêm vào danh sách theo dõi
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionLike(Request $request)
    {
        $storeInfo = $request->input('store');

        return $storeInfo->like(Auth::user(), GlobalConstants::STATUS_INACTIVE) ?
            $this->responseMessage('Đã thêm') :
            $this->responseError('Thất bại');
    }

    /**
     * Xóa khỏi danh sách theo dõi
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionUnlike(Request $request)
    {
        $storeInfo = $request->input('store');
        $storeInfo->unlike();

        $this->settingUserRepo->makeModel()
            ->where('store_id', $storeInfo->getId())
            ->where('user_id', auth()->id())
            ->delete();

        return $this->responseMessage('Đã xóa');
    }


}
