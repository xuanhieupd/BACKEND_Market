<?php

/**
 * Activitylog Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Activitylog
 * @copyright (c) 10.10.2020, HNW
 */

namespace App\Modules\Activitylog\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;

use App\Modules\Activitylog\Models\Entities\Activity;
use App\Modules\Activitylog\Models\Repositories\Contracts\ActivityInterface;
use Illuminate\Support\Collection;

class ActivityRepository extends AbstractRepository implements ActivityInterface
{


    /**
     * Lấy lịch sử hoạt động theo fields
     *
     * @param $classValue
     * @param array $subjectIds
     * @param array $conditions
     * @param array $fetchOptions
     * @return Collection|Activity
     * @author xuanhieupd
     */
    public function getActivitiesBySubjectIds($className, array $subjectIds, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('subject_type', $className)
            ->whereIn('subject_id', $subjectIds)
            ->orderBy('id', 'DESC')
            ->simplePaginate(20);

    }

    /**
     * @return Activity
     */
    public function model()
    {
        return Activity::class;
    }

}
