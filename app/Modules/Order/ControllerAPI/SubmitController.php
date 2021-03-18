<?php

/**
 * Submit Controller
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 05.10.2020
 */

namespace App\Modules\Order\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Customer\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Models\Repositories\Contracts\CustomerInterface;
use App\Modules\Customer\Models\Repositories\Eloquents\CustomerRepository;
use App\Modules\Order\Models\Entities\Order;
use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Models\Repositories\Eloquents\OrderRepository;
use App\Modules\Order\Requests\SubmitRequest;
use App\Modules\Store\Exceptions\StoreNotFoundException;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use App\Modules\Wallet\Exceptions\TransferException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmitController extends AbstractController
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var StoreRepository
     */
    private $storeRepo;

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
    public function __construct(
        OrderInterface $orderRepo,
        StoreInterface $storeRepo,
        CustomerInterface $customerRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->storeRepo = $storeRepo;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Xác nhận hoàn tất toa hàng
     * Nếu trả thừa tiền thì sẽ coi số tiền thừa là khách hàng đặt cọc cho những lần sau
     *
     * @param SubmitRequest $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(SubmitRequest $request)
    {
        $visitor = Auth::user();
        $orderInfo = $request->input('order');

        $storeInfo = $this->storeRepo->getStoreById($orderInfo->getStoreId())->first();
        if (!$storeInfo) return $this->responseError('Không tìm thấy thông tin cửa hàng');

        try {
            $customerInfo = $this->customerRepo->getStoreCustomerById($visitor->getStoreId(), $orderInfo->getCustomerId());
        } catch (CustomerNotFoundException $e) {
            return $this->responseError('Không tìm thấy thông tin khách hàng');
        }

        $totalReceivable = $request->get('total_receivable', 0);
        $totalExpense = $request->get('total_expense', 0);
        $moneyCash = $request->get('money_cash', 0);
        $moneyBanking = $request->get('money_banking', 0);
        $moneyFortune = $request->get('money_fortune', 0);

        $totalPrices = array(
            $customerInfo->getDebtAmount(), // Nợ cũ
            $orderInfo->getTotalPrice(), // Tiền hàng
            $totalReceivable, // Phụ thu
            -$totalExpense, // Phụ chi
            -$moneyFortune // Tiền lộc
        );

        $totalPrice = array_sum($totalPrices); // Tổng tiền khách phải trả
        $customerPayValue = $customerInfo->getBalance() + $moneyCash + $moneyBanking; // Tổng tiền khách thực trả
        $leftAmount = $totalPrice - $customerPayValue; // Số tiền nợ còn lại
        if ($leftAmount > 0) {
            return $this->responseError('Bạn đang trả thừa ' . number_format($leftAmount));
        }

        DB::beginTransaction();
        try {
            $visitor = Auth::user();
            $transferMetadata = array('id' => $orderInfo->getId(), 'class' => get_class($orderInfo));

            $debtLeft = 0;
            $transferResult = $debtLeft < 0 ?
                $customerInfo->forceTransfer($storeInfo, abs($debtLeft), $transferMetadata) :
                $storeInfo->forceTransfer($customerInfo, abs($debtLeft), $transferMetadata);

            if (!$transferResult) {
                throw new TransferException();
            }

            $orderInfo->setAttribute('manager_id', $visitor->getId());
            $orderInfo->setAttribute('money_fortune', $moneyCash);
            $orderInfo->setAttribute('money_cash', $moneyCash);
            $orderInfo->setAttribute('money_banking', $moneyBanking);
            $orderInfo->setAttribute('total_receivable', $totalReceivable);
            $orderInfo->setAttribute('total_expense', $totalExpense);
            $orderInfo->setAttribute('status', Order::ORDER_DONE);
            $orderInfo->save();

            DB::commit();
            return $this->responseMessage('Xác nhận thành công');
        } catch (TransferException $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Xác nhận giao dịch bị lỗi' . $e->getMessage());
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->responseError('Xác nhận thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Log Activity
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function logActivity(Request $request)
    {
        $orderInfo = $request->input('order');

        activity('order')
            ->causedBy(Auth::user())
            ->performedOn($orderInfo)
            ->log('updated.action.done');
    }

}
