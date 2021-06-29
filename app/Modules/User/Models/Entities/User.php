<?php

namespace App\Modules\User\Models\Entities;

use App\Base\Traits\OverrideTableName;
use App\GlobalConstants;
use App\Libraries\Chat\Traits\Messageable;
use App\Modules\Feed\Modules\Comment\Contracts\AuthorInterface;
use App\Modules\Product\Modules\Seen\Traits\Seenable;
use App\Modules\Role\Models\Entities\Role;
use App\Modules\Store\Models\Entities\Store;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable implements Wallet, AuthorInterface
{

    use HasFactory;
    use Notifiable;
    use HasWallet;
    use Seenable;
    use OverrideTableName;
    use Messageable;
    use EntrustUserTrait;

    protected $connection = 'box';
    protected $table = 'hnw_user';
    protected $primaryKey = 'user_id';
    public $timestamps = true;
    public static $tableAlias = 'hnw_user';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'role_id',
        'fullname',
        'email',
        'password',
        'order',
        'status',
        'api_token',
    );

    /**
     * @var string[]
     */
    protected $casts = array(
        'store_id' => 'int'
    );

    /**
     * @var string[]
     */
    protected $hidden = array(
        'password',
        'api_token',
        'remember_token',
    );

    /**
     * Alias for `user_id`
     *
     * @return int
     * @author shin_conan <xuanhieu.pd@gmail.com>
     */
    public function getId()
    {
        return $this->getAttribute('user_id');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'USER';
    }

    /**
     * Họ và tên
     *
     * @return string
     * @author xuanhieupd
     */
    public function getFullName()
    {
        return $this->getAttribute('fullname');
    }

    /**
     * @return string[]
     */
    public function getAllPhoneNumbers()
    {
        return array($this->getPhoneNumber());
    }

    /**
     * AvatarUrl
     *
     * @return string
     * @author xuanhieupd
     */
    public function getAvatarUrl()
    {
        return 'http://via.placeholder.com/150/385898/385898';
    }

    /**
     * Số điện thoại chính
     *
     * @return string
     * @author xuanhieupd
     */
    public function getPhoneNumber()
    {
        $profileInfo = $this->userProfile;
        return $profileInfo ? $profileInfo->getAttribute('telephone') : '';
    }

    /**
     * Địa chỉ chính
     *
     * @return string
     * @author xuanhieupd
     */
    public function getAddress()
    {
        $profileInfo = $this->userProfile;
        return $profileInfo ? $profileInfo->getAttribute('address') : '';
    }

    /**
     * StoreId
     *
     * @return int
     * @author xuanhieupd
     */
    public function getStoreId()
    {
        return $this->getAttribute('store_id');
    }

    /**
     * Thông tin profile
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function userProfile()
    {
        return $this->belongsTo(Profile::class, 'user_id', 'user_id');
    }

    /**
     * Thông tin vai trò
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Thông tin cửa hàng
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function userStore()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    /**
     * Tìm theo người dùng active
     *
     * @param Builder $builder
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('status', GlobalConstants::STATUS_ACTIVE);
    }

    /**
     * Tìm theo credential
     *
     * @param Builder $builder
     * @param $credential
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeCredential(Builder $builder, $credential)
    {
        return $builder->whereHas('userPhones', function ($phoneBuilder) use ($credential) {
            return $phoneBuilder->where('phone_number', $credential);
        });
    }

    public function getCreatedDate()
    {
        $createdAt = $this->getAttribute('created_at');
        return $createdAt ? $this->getAttribute('created_at')->toDateTimeString() : null;
    }

    public function getUpdatedDate()
    {
        $updatedAt = $this->getAttribute('updated_at');
        return $updatedAt ? $this->getAttribute('updated_at')->toDateTimeString() : null;
    }

}
