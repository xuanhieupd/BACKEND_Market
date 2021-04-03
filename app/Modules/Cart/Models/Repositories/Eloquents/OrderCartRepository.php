<?php

/**
 * Order Cart Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Cart
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Cart\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Cart\Models\Entities\OrderCart;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use Illuminate\Support\Collection;

class OrderCartRepository extends AbstractRepository implements OrderCartInterface
{

    /**
     * Lấy danh sách sản phẩm có trong giỏ hàng của $userInfo
     *
     * @param int $userId
     * @return Collection|OrderCart
     * @author xuanhieupd
     */
    public function getCarts(int $userId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('user_id', $userId);
    }

    /**
     * @return OrderCart
     */
    public function model()
    {
        return OrderCart::class;
    }

}
