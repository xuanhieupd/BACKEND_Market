<?php

namespace App\Modules\Category\Modules\StoreCategory\Models\Entities;

use App\Base\AbstractModelRelation;
use App\Modules\Category\Models\Entities\Category;
use App\Modules\Store\Models\Entities\Store;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCategory extends AbstractModelRelation
{

    protected $table = 'hnw_base_category_store';
    protected $primaryKey = array('category_id', 'store_id');
    public static $tableAlias = 'hnw_base_category_store';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'store_id',
        'category_id',
        'total_product',
        'total_variant',
    );

    /**
     * @var string[]
     */
    protected $casts = array(
        'total_product' => 'int',
        'total_variant' => 'int',
    );

    /**
     * Alias for `id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array(
            $this->getAttribute('category_id'),
            $this->getAttribute('store_id'),
        ));
    }

    /**
     * Thông tin cửa hàng
     *
     * @return BelongsTo|Store
     * @author xuanhieupd
     */
    public function storeCategoryStore()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    /**
     * Thông tin danh mục
     *
     * @return BelongsTo|Store
     * @author xuanhieupd
     */
    public function storeCategoryCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
