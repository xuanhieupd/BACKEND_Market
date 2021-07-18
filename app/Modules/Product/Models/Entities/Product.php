<?php

namespace App\Modules\Product\Models\Entities;

use App\Base\AbstractModel;
use App\Base\Filterable;
use App\GlobalConstants;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Brand\Models\Entities\Brand;
use App\Modules\Category\Models\Entities\Category;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Product\Constants\Constants;
use App\Modules\Product\Modules\Attachment\Models\Entities\Attachment as ProductAttachment;
use App\Modules\Product\Modules\Variant\Models\Entities\Variant;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Supplier\Models\Entities\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Modules\Likeable\Traits\Likeable;

class Product extends AbstractModel
{

    use Filterable;
    use Likeable;
    use SoftDeletes;

    protected $connection = 'box';
    protected $table = 'hnw_product';
    protected $primaryKey = 'product_id';
    public static $tableAlias = 'hnw_product';

    /**
     * @var string[]
     */
    protected $filterable = array(
        'store_id',
        'supplier_id',
        'brand_id',
        'category_id',
        'status',
        'state_id',
        'is_special',
    );

    /**
     * @var string[]
     */
    protected $casts = array(
        'current_stock' => 'int',
        'total_stock' => 'int',
    );

    /**
     * Alias for `product_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('product_id');
    }

    /**
     * Mã SKU
     *
     * @return string
     * @author xuanhieupd
     */
    public function getSku()
    {
        return Str::upper($this->getAttribute('sku'));
    }

    /**
     * Tồn hiện tại
     *
     * @return int
     * @author xuanhieupd
     */
    public function getCurrentStock()
    {
        return $this->getAttribute('current_stock');
    }

    /**
     * Tổng tồn
     *
     * @return int
     * @author xuanhieupd
     */
    public function getTotalStock()
    {
        return $this->getAttribute('total_stock');
    }

    /**
     * Địa chỉ ảnh đại diện
     *
     * @return string
     * @author xuanhieupd
     */
    public function getFullImageUrl()
    {
        $attachmentInfo = $this->productAttachment;
        return $attachmentInfo ? $attachmentInfo->getFullUrlAttribute() : $this->getDefaultImageUrl();
    }

    /**
     * Thumbnail ảnh đại diện
     *
     * @return string
     * @author xuanhieupd
     */
    public function getThumbUrl()
    {
        $attachmentInfo = $this->productAttachment;
        return $attachmentInfo ? $attachmentInfo->getThumbnailUrlAttribute() : $this->getDefaultImageUrl();
    }

    /**
     * Ảnh đại diện mặc định
     *
     * @return string
     * @author xuanhieupd
     */
    public function getDefaultImageUrl()
    {
        return 'http://via.placeholder.com/150/385898/385898';
    }

    /**
     * Giá nhập
     *
     * @return int
     * @author xuanhieupd
     */
    public function getImportPrice()
    {
        return $this->getAttribute('import_price');
    }

    /**
     * Giá bán buôn
     *
     * @return int
     * @author xuanhieupd
     */
    public function getWholePrice()
    {
        return $this->getAttribute('whole_price');
    }

    /**
     * Giá bán lẻ
     *
     * @return int
     * @author xuanhieupd
     */
    public function getRetailPrice()
    {
        return $this->getAttribute('retail_price');
    }

    /**
     * Giá cộng tác viên
     *
     * @return int
     * @author xuanhieupd
     */
    public function getCollaboratorPrice()
    {
        return $this->getAttribute('collaborator_price');
    }

    /**
     * Hiển thị giá của app Market
     * @author xuanhieupd
     */
    public function getMarketPrice()
    {
        $canViewPrice = $this->getAttribute('canViewPrice');
        return $canViewPrice ? $this->getWholePrice() : null;
    }

    /**
     * Thông tin danh mục
     *
     * @return BelongsTo|Category
     * @author xuanhieupd
     */
    public function productCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id')
            ->select(array(
                'category_id',
                'title',
                'parent_id',
                'level'
            ));
    }

    /**
     * Thông tin thương hiệu
     *
     * @return BelongsTo|Brand
     * @author xuanhieupd
     */
    public function productBrand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id')
            ->select(array(
                'brand_id',
                'title',
            ));
    }

    /**
     * Thông tin nhà cung cấp
     *
     * @return BelongsTo|Supplier
     * @author xuanhieupd
     */
    public function productSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id')
            ->select(array(
                'supplier_id',
                'fullname',
            ));
    }

    /**
     * Ảnh đại diện của sản phẩm
     *
     * @return BelongsTo|Attachment
     * @author xuanhieupd
     */
    public function productAttachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id', 'attachment_id')->withDefault();
    }

    /**
     * Danh sách ảnh sản phẩm
     *
     * @return BelongsToMany|Attachment
     * @author xuanhieupd
     */
    public function productAttachments()
    {
        return $this->belongsToMany(Attachment::class, (new ProductAttachment())->getTable(), 'product_id', 'attachment_id');
    }

    /**
     * Danh sách mã hàng
     *
     * @return HasMany|Variant
     * @author xuanhieupd
     */
    public function productVariants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'product_id')
            ->select(array(
                'variant_id',
                'product_id',
                'color_id',
                'size_id',
                'current_stock',
                'total_stock',
            ));
    }

    /**
     * Thông tin cửa hàng
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function productStore()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id')->withDefault();
    }

    /**
     * Thông tin cửa hàng với các thông tin cài đặt
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function productStoreOnlySetting()
    {
        return $this->productStore()
            ->select(array(
                'store_id', 'title',
                'public_product', 'public_product_quantity', 'public_product_price'
            ));
    }

    /**
     * Tìm kiếm sản phẩm
     *
     * @param Builder $builder
     * @param $searchQuery
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeSearch(Builder $builder, $searchQuery)
    {
        return blank($searchQuery) ? $builder : $builder
            ->where('sku', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('title', 'LIKE', '%' . $searchQuery . '%');
    }

    /**
     * Sản phẩm avaiable for sale
     *
     * @param Builder $builder
     * @return Builder
     * @author xuanhieupd
     */
    public function scopePublic(Builder $builder)
    {
        return $builder
            ->where(self::wrapDbName('show_on_market'), GlobalConstants::STATUS_ACTIVE)
            ->where(self::wrapDbName('is_over'), GlobalConstants::STATUS_INACTIVE)
            ->where(self::wrapDbName('status'), GlobalConstants::STATUS_ACTIVE)
//            ->whereNotNull('attachment_id')
            ->whereHas('productStore', function ($storeBuilder) {
                $storeBuilder->where('public_product', GlobalConstants::STATUS_ACTIVE);
            });
    }

    /**
     * @param Builder $builder
     * @param $userInfo
     * @return Builder
     */
    public function scopeAvailableForUser(Builder $builder, $userInfo)
    {
        return $builder;
    }

    /**
     * Sắp xếp theo các tiêu chí
     *
     * @param Builder $builder
     * @param $orderId
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeSortBy(Builder $builder, $orderId)
    {
        switch ($orderId) {
            case Constants::ORDER_PRODUCT_DESC:
                return $builder->orderBy('product_id', 'DESC');

            case Constants::ORDER_PRODUCT_ASC:
                return $builder->orderBy('product_id', 'ASC');

            case Constants::ORDER_STOCK_DESC:
                return $builder->orderBy('current_stock', 'DESC');

            case Constants::ORDER_STOCK_ASC:
                return $builder->orderBy('current_stock', 'ASC');

            case Constants::ORDER_PRICE_DESC:
                return $builder->orderBy('whole_price', 'DESC');

            case Constants::ORDER_PRICE_ASC:
                return $builder->orderBy('whole_price', 'ASC');

        }

        return $builder->orderBy('product_id', 'DESC');
    }

    /**
     * Tìm kiếm theo danh mục
     *
     * @param Builder $builder
     * @param $categoryId
     * @return Builder
     */
    public function filterCategoryId(Builder $builder, $categoryId)
    {
        $categoryRepo = app(CategoryInterface::class);
        $categoryInfo = $categoryRepo->getCategoryById($categoryId)->first();
        if (!$categoryInfo) return $builder->where('category_id', -1);

        $siblings = $categoryInfo->descendants()->get(array('category_id'));
        $categoryIds = $siblings->pluck('category_id');
        $categoryIds->push($categoryId);

        return $builder->whereIn('category_id', $categoryIds);
    }


    /**
     * @param Builder $builder
     * @param $startPrice
     * @return Builder
     */
    public function filterStartPrice(Builder $builder, $startPrice)
    {
        return !is_numeric($startPrice) ? $builder : $builder->where('whole_price', '>=', $startPrice);
    }

    /**
     * @param Builder $builder
     * @param $endPrice
     * @return Builder
     */
    public function filterEndPrice(Builder $builder, $endPrice)
    {
        return !is_numeric($endPrice) ? $builder : $builder->where('whole_price', '<=', $endPrice);
    }

    /**
     * @return bool
     */
    public function canView()
    {
        return true;

    }

}
