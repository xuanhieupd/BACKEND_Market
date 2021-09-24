<?php

namespace App\Modules\Auth\ControllerAPI;

use App\Base\AbstractController;
use App\GlobalConstants;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Resources\LoginResource;
use App\Modules\Category\Models\Entities\Category;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Likeable\Models\Entities\Like;
use App\Modules\User\Models\Repositories\Contracts\ProfileInterface;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Repositories\Eloquents\ProfileRepository;
use App\Modules\User\Models\Repositories\Eloquents\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends AbstractController
{

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @var ProfileRepository
     */
    protected $profileRepo;

    /**
     * @var CategoryRepository
     * @copyright (c) 9/23/2021, @ducluu
     */
    protected $categoryRepo;


    /**
     * Constructor.
     *
     * @param UserInterface $userRepo
     * @author xuanhieupd
     */
    public function __construct(UserInterface $userRepo, ProfileInterface $profileRepo, CategoryInterface $categoryRepo)
    {
        $this->userRepo = $userRepo;
        $this->profileRepo = $profileRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * @param RegisterRequest $request
     * @return LoginResource
     * @author xuanhieupd
     */
    public function actionIndex(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $userInfo = $this->userRepo->create(array(
                'assign_id' => null,
                'fullname' => $request->getFullName(),
                'email' => $request->getPhoneNumber(),
                'password' => $request->getHashPassword(),
                'status' => GlobalConstants::STATUS_ACTIVE,
                'last_activity' => now(), 'register_date' => now(),
                'username' => '', 'avatar' => '',
                'order' => 0, 'store_id' => 0,
                'api_token' => Str::random(32),
            ));

            $this->profileRepo->create(array(
                'user_id' => $userInfo->getId(),
                'telephone' => $request->getPhoneNumber(),
                'dob_day' => 0, 'dob_month' => 0, 'dob_year' => 0, 'age' => 0
            ));

            $likeCategories = $request->get('like_cats', array());
            $this->_makeCategoryLike($likeCategories, $userInfo);
            DB::commit();
            return new LoginResource($userInfo);
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::error($exception);
            return $this->responseError();
        }
    }

    /**
     * @param $likeCategories
     * @param $userInfo
     * @copyright (c) 9/23/2021, @ducluu
     */
    protected function _makeCategoryLike($likeCategories, $userInfo){
        $childCats = Category::query()
            ->whereIn('parent_id', $likeCategories)
            ->where('init', GlobalConstants::STATUS_ACTIVE)
            ->select('category_id')->get()->pluck('category_id')->toArray();
        $likeList = array_map(function($item) use($userInfo){
            return array(
                'author_id' => $userInfo->getId(),
                'author_type' => get_class($userInfo),
                'status' => GlobalConstants::STATUS_ACTIVE,
                'likeable_id' => $item,
                'likeable_type' => Category::class,
//                'user_id' => $userInfo->getId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
        }, $childCats);
        Like::query()->insert($likeList);
    }

}
