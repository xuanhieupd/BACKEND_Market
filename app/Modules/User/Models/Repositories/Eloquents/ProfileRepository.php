<?php

namespace App\Modules\User\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\User\Models\Entities\Profile;
use App\Modules\User\Models\Repositories\Contracts\ProfileInterface;

class ProfileRepository extends AbstractRepository implements ProfileInterface
{

    /**
     * @return Profile
     */
    public function model()
    {
        return Profile::class;
    }

}
