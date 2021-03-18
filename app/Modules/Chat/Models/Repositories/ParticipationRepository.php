<?php

namespace App\Modules\Chat\Models\Repositories;

use App\Base\AbstractRepository;
use App\Libraries\Chat\Models\Participation;
use App\Modules\Chat\Models\Repositories\Contracts\ParticipationInterface;

class ParticipationRepository extends AbstractRepository implements ParticipationInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return Participation::class;
    }
}
