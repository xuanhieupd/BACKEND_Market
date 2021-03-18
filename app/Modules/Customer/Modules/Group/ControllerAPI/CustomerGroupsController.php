<?php

namespace App\Modules\Customer\Modules\Group\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Customer\Modules\Group\Models\Repositories\Contracts\GroupInterface;
use App\Modules\Customer\Modules\Group\Models\Repositories\Eloquents\GroupRepository;
use App\Modules\Customer\Modules\Group\Resources\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerGroupsController extends AbstractController
{

    /**
     * @var GroupRepository
     */
    private $groupRepo;

    /**
     * Constructor.
     *
     * @param GroupInterface $groupRepo
     * @author xuanhieupd
     */
    public function __construct(GroupInterface $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * Danh sách nhóm khách hàng
     *
     * @param Request $request
     * @return GroupResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $groups = $this->groupRepo->getStoreCustomerGroups($visitor->getStoreId());

        return GroupResource::collection($groups);
    }

}
