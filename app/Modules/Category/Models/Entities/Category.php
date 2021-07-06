<?php

namespace App\Modules\Category\Models\Entities;

use App\Base\AbstractModel;
use App\Base\Filterable;
use App\Modules\Category\Models\Traits\CategoryNestedModel;
use App\Modules\Category\Modules\StoreCategory\Models\Entities\StoreCategory;
use App\Modules\Product\Models\Entities\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Modules\Likeable\Traits\Likeable;

class Category extends AbstractModel
{
    use CategoryNestedModel;
    use Likeable;
    use Filterable;

    protected $connection = 'box';
    protected $table = 'hnw_category';
    protected $primaryKey = 'category_id';
    public static $tableAlias = 'hnw_category';

    /**
     * Filter
     *
     * @var string[]
     */
    protected $filterable = array(
        'level',
        'level_less_than',
        'parent_id'
    );

    /**
     * @var string[]
     */
    protected $fillable = array(
        'category_id',
        'store_id',
        'code',
        'title',
        'description',
        'icon',
        'parent_id',
        'rgt',
        'lft',
        'level',
        'children_ids',
        'init',
        'status',
    );

    /**
     * @var string[]
     */
    protected $hidden = array(
//        'lft',
//        'rgt',
//        'init'
    );

    /**
     * Alias for `category_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('category_id');
    }

    /**
     * Danh sách sản phẩm
     *
     * @return HasMany
     * @author xuanhieupd
     */
    public function categoryProducts()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    /**
     * Mapping store - category
     *
     * @return HasOne
     * @author xuanhieupd
     */
    public function categoryStoreMap()
    {
        return $this->hasOne(StoreCategory::class, 'category_id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @copyright (c) 4:15 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public function mapParent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'category_id');
    }

    /**
     * Lọc danh mục mà cửa hàng bật
     *
     * @param Builder $builder
     * @param $storeId
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeStoreAvaiable(Builder $builder, $storeId)
    {
        return $builder
            ->where('store_id', $storeId)
            ->orWhere('store_id', 0);
    }

    /**
     * Filter Level nhỏ hơn $level
     *
     * @param Builder $builder
     * @param $level
     * @return Builder
     * @author xuanhieupd
     */
    public function filterLevelLessThan(Builder $builder, $level)
    {
        return $this->scopeLevel($builder, '<=', $level);
    }

    /**
     * Filter Level
     *
     * @param Builder $builder
     * @param $level
     * @return Builder
     * @author xuanhieupd
     */
    public function filterLevel(Builder $builder, $level)
    {
        return $this->scopeLevel($builder, $level);
    }

    /**
     * Scope Level
     *
     * @param Builder $builder
     * @param $level
     * @return Builder
     * @author xuanhieupd
     */
    public function scopeLevel(Builder $builder, $level)
    {
        return is_numeric($level) ? $builder->where('level', $level - 1) : $builder;
    }
}
