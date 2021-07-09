<?php

/**
 * Bảng tin
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Feed\Models\Entities;

use App\Base\AbstractModel;
use App\Base\Filterable;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Feed\Modules\Comment\Models\Entities\Comment;
use App\Modules\Likeable\Traits\Likeable;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Modules\Feed\Modules\Attachment\Models\Entities\Attachment as FeedAttachment;
use App\Modules\Feed\Modules\Product\Models\Entities\Product as FeedProduct;
use App\Modules\Likeable\Models\Entities\Like;

class Feed extends AbstractModel
{
    use Likeable;
    use Filterable;

    protected $table = 'hnw_feed';
    protected $primaryKey = 'feed_id';
    public $timestamps = true;
    public $incrementing = true;
    public static $tableAlias = 'hnw_feed';

    /**
     * The attributes that are mass assignable.
     *
     * @author xuanhieupd
     * @var array
     */
    const TYPE_USER = 'USER';
    const TYPE_STORE = 'STORE';

    protected $fillable = array(
        'author_type',
        'author_id',
        'title',
        'description',
    );

    /**
     * Casts
     *
     * @var array
     */
    protected $casts = array(
        'attachment_ids' => 'json',
        'product_ids' => 'json'
    );

    /**
     * Alias for `feed_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('feed_id');
    }

    /**
     * Tác giả của bài đăng
     *
     * @return MorphTo
     * @author xuanhieupd
     */
    public function feedAuthor()
    {
        return $this->morphTo('feedAuthor', 'author_type', 'author_id');
    }

    /**
     * Ảnh đính kèm
     *
     * @return BelongsToMany
     * @author xuanhieupd
     */
    public function feedAttachments()
    {
        return $this->belongsToMany(Attachment::class, (new FeedAttachment())->getTable(), 'feed_id', 'attachment_id');
    }

    /**
     * Id pivot
     *
     * @return HasMany
     * @author xuanhieupd
     */
    public function feedProductsPivot()
    {
        return $this->hasMany(FeedProduct::class, 'feed_id', 'feed_id');
    }

    /**
     * Danh sách sản phẩm
     *
     * @return BelongsToMany
     * @author xuanhieupd
     */
    public function feedProducts()
    {
        $feedProductTable = (new FeedProduct())->getTable();
        return $this->belongsToMany(Product::class, $feedProductTable, 'feed_id', 'product_id')
            ->select($feedProductTable . '.product_id', 'attachment_id', 'sku', 'title', 'retail_price', 'whole_price', 'collaborator_price', 'import_price', 'category_id')
            ->with(array('productAttachment', 'productStoreOnlySetting'));
    }

    /**
     * @param Builder $builder
     * @param $userId
     * @return Builder
     */
    public function filterUserId(Builder $builder, $userId)
    {
        return $builder
            ->where('author_type', User::class)
            ->where('author_id', $userId);
    }

    /**
     * @param Builder $builder
     * @param $authorType
     * @return Builder
     * @copyright (c) 10:39 AM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public function filterAuthorType(Builder $builder, $authorType)
    {
        return $builder
            ->where('author_type', $authorType);
    }

    /**
     * @param Builder $builder
     * @param $storeId
     * @return Builder
     */
    public function filterStoreId(Builder $builder, $storeId)
    {
        return $builder
            ->where('author_type', Store::class)
            ->where('author_id', $storeId);
    }

    /**
     * @param Builder $builder
     * @param array $categoryIds
     * @return Builder
     * @copyright (c) 4:05 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public function filterCategoryIds(Builder $builder, array $categoryIds)
    {
        return $builder
            ->whereHas('feedProductsPivot', function($q) use($categoryIds){
                $q->whereIn('category_id', $categoryIds);
            });
    }

    /**
     * Thông tin người like cuối cùng
     *
     * @return HasOne
     * @author xuanhieupd
     */
    public function feedLike()
    {
        return $this->hasOne(Like::class, 'likeable_id', 'feed_id')
            ->where('likeable_type', get_class($this))
            ->orderBy('id', 'DESC');
    }

    /**
     * Danh sách thích
     *
     * @return HasMany
     * @author xuanhieupd
     */
    public function feedLikes()
    {
        return $this->hasMany(Like::class, 'feed_id', 'likeable_id')
            ->where('likeable_type', get_class($this))
            ->orderBy('id', 'DESC');
    }

    /**
     * Danh sách bình luận
     *
     * @return HasMany
     * @author xuanhieupd
     */
    public function feedComments()
    {
        return $this->hasMany(Comment::class, 'feed_id', 'feed_id');
    }

}
