<?php

/**
 * Comment
 *
 * @author xuanhieupd
 * @package Feed
 * @copyright (c) 06.10.2020, HNW
 */

namespace App\Modules\Feed\Modules\Comment\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\Feed\Models\Entities\Feed;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends AbstractModel
{

    protected $table = 'hnw_feed_comment';
    protected $primaryKey = 'comment_id';
    public $timestamps = true;
    public $incrementing = true;
    public static $tableAlias = 'hnw_feed_comment';

    /**
     * The attributes that are mass assignable.
     *
     * @author xuanhieupd
     * @var array
     */
    protected $fillable = array(
        'feed_id',
        'author_type',
        'author_id',
        'message',
    );

    /**
     * Alias for `comment_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('comment_id');
    }

    /**
     * Thông tin người bình luận
     *
     * @return MorphTo
     * @author xuanhieupd
     */
    public function commentAuthor()
    {
        return $this->morphTo('author');
    }

    /**
     * Thông tin bảng tin
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function commentFeed()
    {
        return $this->belongsTo(Feed::class, 'feed_id', 'feed_id');
    }

}
