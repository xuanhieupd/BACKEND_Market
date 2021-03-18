<?php

namespace App\Modules\Product\Modules\Seen\Models\Entities;

use App\Base\AbstractModelRelation;
use App\Modules\Product\Models\Entities\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seen extends AbstractModelRelation
{

    protected $table = 'hnw_product_user_seen';
    protected $primaryKey = array('product_id', 'user_id');
    public static $tableAlias = 'hnw_product_user_seen';

    /**
     * @var string[]
     */
    protected $fillable = array(
        'product_id',
        'user_id',
    );

    /**
     * Alias Id
     *
     * @return string
     * @author xuanhieupd
     */
    public function getId()
    {
        return implode('_', array($this->getAttribute('product_id'), $this->getAttribute('user_id')));
    }

    /**
     * Thông tin sản phẩm
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function seenProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id')
            ->select(array(
                'product_id', 'sku', 'title', 'attachment_id',
                'import_price', 'whole_price', 'retail_price', 'collaborator_price',
            ))
            ->public();
    }
}
