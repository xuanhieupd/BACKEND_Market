<?php

namespace App\Modules\Activitylog\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Activitylog\Models\Repositories\Contracts\ActivityInterface;
use Illuminate\Http\Request;

class ActivitiesController extends AbstractController
{
    private $activityRepo;

    /**
     * Constructor.
     *
     * @param ActivityInterface $activityRepo
     * @author xuanhieupd
     */
    public function __construct(ActivityInterface $activityRepo)
    {
        $this->activityRepo = $activityRepo;
    }

    /**
     * Danh sách hoạt động
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {

    }

}
