<?php

/**
 * Order Model
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package User
 * @copyright (c) 03.10.2020, HNW
 */

namespace App\Modules\Order\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Customer\Models\Entities\Customer;
use App\Modules\Order\Events\EItemUpdated;
use App\Base\Filterable;
use App\Modules\Order\Models\Repositories\Contracts\OrderInterface;
use App\Modules\Order\Modules\Note\Models\Entities\Note;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Entities\User;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Order extends AbstractModel
{
    use HasWallet;
    use Filterable;

    const STATUS_QUOTATION_WAITING = 0; // Chờ báo giá
    const STATUS_QUOTATION_PENDING = 1; // Chờ bên khách hàng xác nhận giá
    const ORDER_PENDING = 2; // Đã báo giá thành công - chờ luồng xác nhận phía cửa hàng
    const STATUS_WAREHOUSE_PENDING = 3; // Gửi tới kho
    const STATUS_WAREHOUSE_CONFIRMED = 4; // Kho đã xác nhận
    const STATUS_CONFIRMED = 5; // Toa đã xác nhận
    const STATUS_CANCEL = 6; // Toa hủy

    /* List hành động */
    const ACTION_RA_LOC = 1; // Ra lộc
    const ACTION_DAT_COC = 2; // Đặt cọc
    const ACTION_NO_LAI = 3; // Nợ lại

    protected $table = 'hnw_order';
    protected $primaryKey = 'order_id';
    public static $tableAlias = 'hnw_order';

    /**
     * Fillable
     *
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'customer_id',
        'user_id',
        'warehouse_id',
        'manager_id',
        'action_id',
        'code',
        'money_deposit', // Tiền khách đặt cọc
        'money_fortune', // Tiền khách được lộc từ cửa hàng (Ra lộc)
        'total_quantity',
        'total_price',
        'total_receivable', // Phụ thu - Cửa hàng thu của khách
        'total_expense', // Phụ chi - Cửa hàng chịu phí
        'money_banking',
        'money_cash',
        'money_deposit',
        'has_change_quantity',
        'has_change_price',
        'status',
        'note',
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
     * @author shin_conan <xuanhieu.pd@gmail.com>
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
        return $builder->where('code', 'LIKE', '%' . $searchQuery . '%')
            ->orWhereHas('orderCustomer', function ($customerBuilder) use ($searchQuery) {
                $customerBuilder
                    ->select(array('fullname'))
                    ->where('fullname', 'LIKE', '%' . $searchQuery . '%');
            })
            ->orWhereHas('orderCustomer.customerPhones', function ($phoneBuilder) use ($searchQuery) {
                $phoneBuilder
                    ->select(array('phone_number'))
                    ->where('phone_number', 'LIKE', '%' . $searchQuery . '%');
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
