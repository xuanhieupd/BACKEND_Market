<?php

/**
 * Seen Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Seen
 * @copyright (c) 08.02.2021, HNW
 */

namespace App\Modules\Product\Modules\Seen\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Product\Modules\Seen\Models\Entities\Seen;
use App\Modules\Product\Modules\Seen\Models\Repositories\Contracts\SeenInterface;
use Illuminate\Support\Collection;

class SeenRepository extends AbstractRepository implements SeenInterface
{

    /**
     * @param $userId
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection
     */
    public function getProductsByUserId($userId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('user_id', $userId);
    }

    /**
     * @return Seen
     */
    public function model()
    {
        return Seen::class;
    }

}
