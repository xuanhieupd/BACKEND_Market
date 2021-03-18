<?php


namespace App\Modules\Customer\Modules\PhoneNumber\ControllerAPI;


use App\Base\AbstractController;
use App\Modules\Customer\Modules\PhoneNumber\Models\Repositories\Contracts\CustomerPhoneInterface;
use App\Modules\Customer\Modules\PhoneNumber\Models\Repositories\Eloquents\CustomerPhoneRepository;
use Illuminate\Http\Request;
use App\Modules\Customer\Modules\PhoneNumber\Resources\PhoneNumberResource;

class PhoneNumbersController extends AbstractController
{

    /**
     * @var CustomerPhoneRepository
     */
    private $phoneRepo;

    /**
     * Constructor.
     *
     * @param CustomerPhoneInterface $phoneRepo
     * @author xuanhieupd
     */
    public function __construct(CustomerPhoneInterface $phoneRepo)
    {
        $this->phoneRepo = $phoneRepo;
    }

    /**
     * Danh sách số điện thoại của khách hàng
     *
     * @param Request $request
     * @return
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $customerInfo = $request->input('customer');
        $phoneNumbers = $this->phoneRepo->getCustomerAddresses($customerInfo->getId());

        return PhoneNumberResource::collection($phoneNumbers);
    }

}
