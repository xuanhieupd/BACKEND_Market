<?php

/**
 * Store Model
 *
 * @author xuanhieupd
 * @package Store
 * @copyright (c) 04.10.2020, HNW
 */

namespace App\Modules\Store\Models\Entities;

use App\Base\AbstractModel;
use App\Base\Filterable;
use App\GlobalConstants;
use App\Libraries\Chat\Traits\Messageable;
use App\Modules\Feed\Modules\Comment\Contracts\AuthorInterface;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Store\Modules\SettingUser\Traits\Settingable;
use App\Modules\User\Models\Entities\User;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use App\Modules\Likeable\Traits\Likeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Store extends AbstractModel implements Wallet, AuthorInterface
{
    use HasWallet;
    use Likeable;
    use Settingable;
    use Filterable;
    use Messageable;

    protected $connection = 'box';
    protected $table = 'hnw_store';
    protected $primaryKey = 'store_id';
    public static $tableAlias = 'hnw_store';

    /**
     * @var string[]
     */
    protected $filterable = array(
        'is_special'
    );

    /**
     * @var string[]
     */
    protected $fillable = array(
        'user_id',
        'avatar',
        'background',
        'title',
        'collaborator_price_percent',
        'retail_price_percent',
        'address',
        'telephone',
        'bank_account_number',
        'public_product',
        'public_product_quantity',
        'public_product_price',
        'allow_auto_price'
    );

    /**
     * Alias for `store_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('store_id');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'STORE';
    }

    /**
     * FullName
     *
     * @return string
     * @author xuanhieupd
     */
    public function getFullName()
    {
        return $this->getAttribute('title');
    }

    /**
     * Avatar Url
     *
     * @return string
     * @author xuanhieupd
     */
    public function getAvatarUrl()
    {
//        $avatarName = $this->getAttribute('avatar');
//        return filter_var($avatarName, FILTER_VALIDATE_URL) ?
//            $avatarName :
//            'http://via.placeholder.com/150/385898/385898';


        $imageValue = $this->getAttribute('avatar');

        if (blank($imageValue)) {
            return url('http://via.placeholder.com/150/385898/385898');
        }

        return (!filter_var($imageValue, FILTER_VALIDATE_URL)) ?
            Storage::disk('cdn')->url($imageValue) :
            $imageValue;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     * @copyright (c) 3:00 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public function getBackgroundUrlAttribute()
    {
        $imageValue = $this->getAttribute('background');

        if (blank($imageValue)) {
            return url('http://via.placeholder.com/150/385898/385898');
        }

        return (!filter_var($imageValue, FILTER_VALIDATE_URL)) ?
            Storage::disk('cdn')->url($imageValue) :
            $imageValue;
    }


    /**
     * @return array
     */
    public function getAllPhoneNumbers()
    {
        $strPhone = $this->getAttribute('telephone');
        $phoneSplits = explode('|', $strPhone);

        $phoneNumbers = [];

        foreach ($phoneSplits as $phoneItem) {
            if (blank($phoneItem)) continue;

            $phoneItem = str_replace(array('.', '-', ','), '', $phoneItem);
            preg_match('/\d+/', $phoneItem, $matches);
            if (!isset($matches[0]) || blank($matches[0])) continue;

            $phoneNumbers[] = $matches[0];
        }

        return $phoneNumbers;
    }

    /**
     * Công khai sản phẩm
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isPublicProduct()
    {
        return $this->getAttribute('public_product', GlobalConstants::STATUS_ACTIVE);
    }

    /**
     * Công khai giá
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isPublicPrice()
    {
        return $this->getAttribute('public_product_price', GlobalConstants::STATUS_ACTIVE);
    }

    /**
     * Công khai số lượng
     *
     * @return bool
     * @author xuanhieupd
     */
    public function isPublicQuantity()
    {
        return $this->getAttribute('public_product_quantity', GlobalConstants::STATUS_ACTIVE);
    }

    /**
     * Số tiền cửa hàng này đang nợ
     * Biểu thị bởi số tiền trong ví < 0
     *
     * @return int
     * @author xuanhieupd
     */
    public function getDebtAmount(): int
    {
        $balance = $this->getBalanceAttribute();
        return $balance < 0 ? abs($balance) : 0;
    }

    /**
     * Số tiền cửa hàng đang có
     *
     * @return float|int
     * @author xuanhieupd
     */
    public function getBalance(): int
    {
        $balance = $this->getBalanceAttribute();
        return $balance > 0 ? abs($balance) : 0;
    }

    /**
     * @param User $userInfo
     * @return bool
     */
    public function liked(User $userInfo)
    {
        return DB::connection('box')->table('hnw_follower_user')
            ->where('user_id', $userInfo->getId())
            ->where('store_id', $this->getId())
            ->exists();
    }

    /**
     * Danh sách sản phẩm
     *
     * @return HasMany
     * @author xuanhieupd
     */
    public function storeProducts()
    {
        return $this->hasMany(Product::class, 'store_id', 'store_id');
    }

    /**
     * @param Builder $builder
     * @param $searchQuery
     * @return Builder
     */
    public function scopeSearch(Builder $builder, $searchQuery)
    {
        return blank($searchQuery) ? $builder : $builder->where('title', 'LIKE', '%' . $searchQuery . '%');
    }
}
