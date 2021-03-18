<?php

/**
 * Customer Middleware
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Customer
 * @copyright (c) 04.10.20202, HNW
 */

namespace App\Modules\Customer\Middleware;

use App\Modules\Customer\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Repositories\Eloquents\CustomerRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{

    /**
     * @var CustomerRepository
     */
    protected $customerRepo;

    /**
     * CustomerMiddleware constructor.
     */
    public function __construct(CustomerInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @author xuanhieupd
     */
    public function handle(Request $request, Closure $next)
    {
        $visitor = Auth::user();

        try {
            $customerId = $this->getCustomerIdFromInput($request);
            $customerInfo = $this->customerRepo->getStoreCustomerById($visitor->getStoreId(), $customerId);

            $request->merge(array('customer' => $customerInfo));
            return $next($request);
        } catch (CustomerNotFoundException $e) {
            return response()->responseError('Không tìm thấy thông tin khách hàng', 400);
        }
    }

    /**
     * Lấy tham số customer_id
     *
     * @param Request $request
     * @return int|null
     * @throws CustomerNotFoundException
     * @author xuanhieupd
     */
    protected function getCustomerIdFromInput(Request $request)
    {
        if ($request->route('customerId')) {
            return $request->route('customerId');
        }

        if ($request->has('customer_id') && $request->get('customer_id')) {
            return $request->get('customer_id');
        }

        throw new CustomerNotFoundException();
    }

}
