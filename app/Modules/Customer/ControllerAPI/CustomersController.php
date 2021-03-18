<?php
/**
 * Index Controller
 *
 * @author xuanhieupd
 * @package Customer
 * @copyright 03.10.2020, HNW
 */

namespace App\Modules\Customer\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Repositories\Eloquents\CustomerRepository;
use App\Modules\Customer\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends AbstractController
{
    /**
     * @var CustomerRepository
     */
    private $customerRepo;

    /**
     * Constructor.
     *
     * @return void
     * @author xuanhieupd
     */
    public function __construct(CustomerInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    /**
     * Hiển thị danh sách khách hàng
     *
     * @param Request $request
     * @return CustomerResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $customers = $this->customerRepo->getStoreCustomers($visitor->getStoreId());

        return CustomerResource::collection($customers);
    }

}


