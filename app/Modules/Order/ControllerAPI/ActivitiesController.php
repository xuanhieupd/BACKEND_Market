<?php

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Activitylog\Models\Repositories\Contracts\ActivityInterface;
use App\Modules\Activitylog\Models\Repositories\Eloquents\ActivityRepository;
use App\Modules\Activitylog\Resources\ActivityResource;
use App\Modules\Order\Models\Entities\Item;
use App\Modules\Order\Models\Repositories\Contracts\ItemInterface;
use App\Modules\Order\Models\Repositories\Eloquents\ItemRepository;
use Illuminate\Http\Request;

class ActivitiesController extends AbstractController
{

    /**
     * @var ItemRepository
     */
    private $itemRepo;

    /**
     * @var ActivityRepository
     */
    private $activityRepo;

    /**
     * Constructor.
     *
     * @param ItemInterface $itemRepo
     * @param ActivityInterface $activityRepo
     * @author xuanhieupd
     */
    public function __construct(
        ItemInterface $itemRepo,
        ActivityInterface $activityRepo
    )
    {
        $this->itemRepo = $itemRepo;
        $this->activityRepo = $activityRepo;
    }

    /**
     * Hoạt động trong toa
     *
     * @param Request $request
     * @return ActivityResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $orderInfo = $request->input('order');

        $items = $this->itemRepo->getItemsByOrderId($orderInfo->getId(), array(), array(
            'fields' => array('item_id'),
        ));

        $fetchOptions = array(
            'withs' => array('causer', 'subject')
        );

        $activities = $this->activityRepo->getActivitiesBySubjectIds(Item::class, $items->pluck('item_id')->toArray(), array(), $fetchOptions);
        return ActivityResource::collection($activities);
    }

}
