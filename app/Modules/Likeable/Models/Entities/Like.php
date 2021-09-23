<?php

namespace App\Modules\Likeable\Models\Entities;

use App\Base\AbstractModel;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends AbstractModel
{

    protected $table = 'hnw_likeable_like';
    public $timestamps = true;

    /**
     * @var string[]
     */
    protected $fillable = array(
        'likeable_id',
        'likeable_type',
//        'user_id',
        'status',
        'author_type',
        'author_id'
    );

    /**
     * Alias for `id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * Thông tin người like
     *
     * @return BelongsTo
     * @author xuanhieupd
     */
    public function likeUser()
    {
        return $this->belongsTo(User::class, 'author_id', 'user_id')
            ->select(array('user_id', 'fullname', 'avatar'))
            ->withDefault();
    }

    public function likeAuthor() {
        return $this->morphTo('author');
    }

    /**
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }

}
