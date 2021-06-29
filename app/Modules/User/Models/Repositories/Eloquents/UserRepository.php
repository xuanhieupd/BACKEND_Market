<?php

/**
 * User Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package User
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\User\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;

use App\Modules\Role\Models\Entities\Role;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use App\Modules\User\Models\Entities\User;
use Illuminate\Support\Collection;

class UserRepository extends AbstractRepository implements UserInterface
{

    /**
     * @param $userId
     * @return $this
     */
    public function getUserById($userId)
    {
        return $this->makeModel()
            ->where('user_id', $userId);
    }

    /**
     * Lấy danh sách người dùng trong cửa hàng
     *
     * @param $storeId
     * @return Collection|User
     * @author xuanhieupd
     */
    public function getStoreUsers($storeId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->get();
    }

    /**
     * Tìm người dùng theo tên đăng nhập (username + email + phoneNumber)
     *
     * @param string $credential
     * @return User
     * @throws UserNotFoundException
     * @author xuanhieupd
     */
    public function getUserByCredential(string $credential)
    {
        $userInfo = $this->makeModel()
            ->select(array(
                'user_id',
                'store_id',
                'fullname',
                'password',
                'status',
                'api_token',
            ))
            ->active()
            ->where('email', $credential)
            ->first();

        if (!$userInfo) {
            throw new UserNotFoundException();
        }

        return $userInfo;
    }

    /**
     * Lấy danh sách người dùng thuộc cấp dưới của $rootId
     *
     * @param $storeId
     * @param $rootId
     * @return Collection|User
     * @author xuanhieupd
     */
    public function getStoreSlaveUsersBySupervisor($storeId, User $supervisorInfo, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('store_id', $storeId)
            ->where('user_id', '>=', $supervisorInfo->getLft())
            ->where('user_id', '<=', $supervisorInfo->getRgt())
            ->get();
    }

    public function getAdminUsers($storeId)
    {
        return $this->makeModel()
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', array(Role::$administrativeName));
            });
    }

    public function register($fillParams)
    {

    }

    /**
     * @return User
     */
    public function model()
    {
        return User::class;
    }

}
