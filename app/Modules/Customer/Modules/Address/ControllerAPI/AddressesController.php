<?php

namespace App\Modules\Customer\Modules\Address\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Customer\Modules\Address\Models\Repositories\Contracts\CustomerAddressInterface;
use App\Modules\Customer\Modules\Address\Models\Repositories\Eloquents\CustomerAddressRepository;
use App\Modules\Customer\Modules\Address\Resources\AddressResource;
use App\Modules\Customer\Resources\CustomerDetailResource;
use Illuminate\Http\Request;

class AddressesController extends AbstractController
{

    /**
     * @var CustomerAddressRepository
     */
    private $addressRepo;

    /**
     * Constructor.
     *
     * @param CustomerAddressInterface $addressRepo
     * @author xuanhieupd
     */
    public function __construct(CustomerAddressInterface $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }

    /**
     * Danh sách địa chỉ khách hàng
     *
     * @param Request $request
     * @return
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $customerInfo = $request->input('customer');
        $addresses = $this->addressRepo->getCustomerAddresses($customerInfo->getId());

        return AddressResource::collection($addresses);
    }

}
