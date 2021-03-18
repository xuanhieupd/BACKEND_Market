<?php

/**
 * Change Customer Request
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 08.10.2020, HNW
 */

namespace App\Modules\Order\Requests\Change;

use App\Base\AbstractRequest;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Repositories\Eloquents\CustomerRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class CustomerRequest extends AbstractRequest
{

    /**
     * @var CustomerRepository
     */
    private $customerRepo;

    /**
     * Constructor.
     *
     * @author xuanhieupd
     */
    public function __construct(CustomerInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'customer_id' => 'required|numeric',
        );
    }

    /**
     * Logic validator
     *
     * @param Validator $validator
     * @return MessageBag|null
     */
    public function afterValidator(Validator $validator)
    {
        $visitor = Auth::user();
        $customerId = $this->get('customer_id');

        $isExists = $this->customerRepo->checkExistsStoreCustomerById($visitor->getStoreId(), $customerId);
        if (!$isExists) {
            return $validator->errors()->add('customer', 'Không tìm thấy thông tin khách hàng');
        }

        return null;
    }


}
