<?php

/**
 * Order Model
 *
 * @author xuanhieupd
 * @package User
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Models\Entities;

use App\Base\AbstractModel;
use App\Base\Filterable;
use App\Modules\Customer\Models\Entities\Customer;
use App\Modules\Order\Events\EItemUpdated;
use App\Modules\Order\Modules\Note\Models\Entities\Note;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Entities\User;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Order extends AbstractModel
{
    use HasWallet;
    use Filterable;

    const STATUS_QUOTATION_STORE_WAITING = -4; // Chờ cửa hàng báo giá
    const STATUS_QUOTATION_CUSTOMER_WAITING_CONFIRM = -3; // Chờ khách hàng xác nhận về giá
    const ORDER_DRAFT = -2; // Toa nháp
    const ORDER_PENDING = -1; // Nhân viên bán hàng gửi giỏ hàng tới cho quản lý
    const ORDER_PENDING_WAREHOUSE = 0; // Nhân viên kho đang nhặt hàng
    const ORDER_WAREHOUSE_CONFIRM = 1; // Nhân viên kho xác nhận
    const ORDER_DONE = 2; // Đơn hàng hoàn tất

    /* List hành động */
    const ACTION_RA_LOC = 1; // Ra lộc
    const ACTION_DAT_COC = 2; // Đặt cọc
    const ACTION_NO_LAI = 3; // Nợ lại

    protected $connection = 'box';
    protected $table = 'hnw_order';
    protected $primaryKey = 'order_id';
    public static $tableAlias = 'hnw_order';

    /**
     * Fillable
     *
     * @var string[]
     */
    protected $fillable = array(
        'assign_id',
        'store_id',
        'relation_store_id',
        'customer_user_id',
        'customer_id',
        'user_id',
        'user_relation_id',
        'user_manager_id',
        'user_warehouse_id',
        'action_id',
        'bill_code',
        'deposit',
        'debt_info',
        'total_quantity',
        'total_receivable',
        'total_expense',
        'total_import_price',
        'total_price',
        'money_cash',
        'money_banking',
        'note',
        'status',
        'expand_state',
        'transfer_id',
    );

    /**
     * Casts
     *
     * @var string[]
     */
    protected $casts = array(
        'total_expense' => 'int',
        'total_receivable' => 'int',
        'total_quantity' => 'int',
        'total_price' => 'int',
        'money_banking' => 'int',
        'money_cash' => 'int',
        'has_change_quantity' => 'boolean',
        'has_change_price' => 'boolean',
    );

    /**
     * Filter fields
     *
     * @var string[]
     */
    protected $filterable = array(
        'search',
        'user_id',
        'customer_id',
        'status',
    );

    /**
     * Alias for `order_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('order_id');
    }

    /**
     * Alias for `store_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getStoreId()
    {
        return $this->getAttribute('store_id');
    }

    /**
     * Alias for `customer_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getCustomerId()
    {
        return $this->getAttribute('customer_id');
    }

    /**
     * Trạng thái toa hàng
     *
     * @return int
     * @author xuanhieupd
     */
    public function getStatus()
    {
        return $this->getAttribute('status');
    }

    /**
     * Toa hàng đã hoàn thành hay chưa ?
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isComplete()
    {
        return $this->getStatus() == self::ORDER_DONE;
    }

    /**
     * Có sự thay đổi số lượng
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isChangedQuantity()
    {
        $changedQuantity = $this->getAttribute('has_change_quantity');
        return !!$changedQuantity;
    }

    /**
     * Có sự thay đổi giá
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isChangedPrice()
    {
        $changedPrice = $this->getAttribute('has_change_price');
        return !!$changedPrice;
    }

    /**
     * Tổng giá đơn hàng
     *
     * @param int $extendAmount
     * @return int
     * @author xuanhieupd
     */
    public function getTotalPrice()
    {
        return $this->getAttribute('total_price');
    }

    /**
     * Tổng giá mà khách hàng cần phải trả
     * Tổng = Tổng + Phụ thu - Phụ chi
     *
     * @return int
     * @author xuanhieupd
     */
    public function getTotalPriceNeedPay()
    {
        return ($this->getTotalPrice() + $this->getAttribute('total_receivable')) - $this->getAttribute('total_expense');
    }

    /**
     * Generate code
     *
     * @param $code
     * @author xuanhieupd
     */
    public function setCodeAttribute($code = null)
    {
        $code = !is_null($code) ? $code : implode('_', array(date('dmY'), Str::upper(Str::random(4))));
        $this->attributes['code'] = $code;
    }

    /**
     * Thông tin người tạo toa
     *
     * @return User
     * @author xuanhieupd
     */
    public function orderUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
            ->select(array('user_id', 'fullname', 'avatar'));
    }

    /**
     * @return BelongsTo
     */
    public function orderCustomerUser()
    {
        return $this->belongsTo(User::class, 'customer_user_id', 'user_id');
    }

    /**
     * Thông tin người nhặt hàng
     *
     * @return User
     * @author xuanhieupd
     */
    public function orderUserWarehouse()
    {
        return $this->belongsTo(User::class, 'warehouse_id', 'user_id')
            ->select(array('user_id', 'fullname'));
    }

    /**
     * Thông tin người tạo toa
     *
     * @return User
     * @author xuanhieupd
     */
    public function orderUserManager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'user_id')
            ->select(array('user_id', 'fullname'));
    }

    /**
     * Thông tin cửa hàng
     *
     * @return Store
     * @author xuanhieupd
     */
    public function orderStore()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id')
            ->withDefault();
    }

    /**
     * Thông tin khách hàng
     *
     * @return Customer
     * @author xuanhieupd
     */
    public function orderCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Danh sách sản phẩm có trong toa hàng
     *
     * @return Collection|Item
     * @author xuanhieupd
     */
    public function orderProducts()
    {
        return $this->hasMany(Item::class, 'order_id', 'order_id');
    }

    /**
     * Danh sách ghi chú
     *
     * @return HasMany|Note
     * @author xuanhieupd
     */
    public function orderNotes()
    {
        return $this->hasMany(Note::class, 'order_id', 'order_id');
    }


    /**
     * Dispatch event thay đổi số lượng
     *
     * @return void
     * @author xuanhieupd
     */
    public function tapEventItemUpdated()
    {
        event(new EItemUpdated($this));
    }

    /**
     * Tìm kiếm toa hàng
     *
     * @param Builder $builder
     * @param $searchQuery
     * @return Builder
     * @author xuanhieupd
     */
    public function filterSearch(Builder $builder, $searchQuery)
    {
        if (blank($searchQuery)) return $builder;

        return $builder->where(function ($nestedQuery) use ($searchQuery) {
            $searchQueryQuote = '%' . $searchQuery . '%';

            return $nestedQuery
                ->where('bill_code', 'LIKE', $searchQueryQuote)
                ->orWhereHas('orderCustomerUser', function ($builder) use ($searchQueryQuote) {
                    $builder->where('fullname', 'LIKE', $searchQueryQuote);
                })
                ->orWhereHas('orderCustomerUser.userProfile', function ($builder) use ($searchQueryQuote) {
                    $builder->where('telephone', 'LIKE', $searchQueryQuote);
                });
        });
    }

    /**
     * Tìm kiếm toa hàng
     *
     * @param Builder $builder
     * @param $productId
     * @return Builder
     * @author xuanhieupd
     */
    public function filterProductId(Builder $builder, $productId)
    {
        return $builder->whereHas('orderProducts', function ($builder) use ($productId) {
            $builder->where('product_id', $productId);
        });
    }

    /**
     * Được phép truy cập vào các toa của các nhân viên cấp dưới và của chính mình
     *
     * @param Builder $query
     * @param array $param
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeFilterSlave(Builder $builder, User $supervisorInfo, array $slaveIds = array())
    {
        $slaveIds[] = $supervisorInfo->getId();
        return $builder->whereIn('user_id', $slaveIds);
    }

    /**
     * Scope lọc user chỉ được xem các toa có trạng thái thuộc quyền của mình
     * Ví dụ: tài khoản kho thì chỉ nhìn thấy trạng thái "chờ nhặt hàng" và "đã nhặt"
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeViewStatuses(Builder $builder, $statusIds)
    {
        return $builder->whereIn('status', $statusIds);
    }

}
