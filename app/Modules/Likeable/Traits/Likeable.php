<?php

namespace App\Modules\Likeable\Traits;

use App\GlobalConstants;
use App\Modules\Likeable\Models\Entities\Like;
use App\Modules\Likeable\Models\Entities\LikeCounter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Likeable
{
    public static function bootLikeable()
    {
        if (static::removeLikesOnDelete()) {
            static::deleting(function ($model) {
                /** @var Likeable $model */
                $model->removeLikes();
            });
        }
    }

    /**
     * Populate the $model->likes attribute
     */
    public function getLikeCountAttribute()
    {
        return $this->likeCounter ? $this->likeCounter->count : 0;
    }

    /**
     * Add a like for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     * @return bool
     */
    public function like($authorInfo = null, $statusId = GlobalConstants::STATUS_ACTIVE)
    {
        $authorInfo = $authorInfo ? $authorInfo : Auth::user();
        if (!$authorInfo) return;

        $likeInfo = $this->likes()
            ->where('author_id', $authorInfo->getId())
            ->where('author_type', get_class($authorInfo))
            ->first();

        if ($likeInfo) return true;

        $likeInfo = new Like();
        $likeInfo->setAttribute('author_id', $authorInfo->getId());
        $likeInfo->setAttribute('author_type', get_class($authorInfo));
        $likeInfo->setAttribute('status', $statusId);
        $this->likes()->save($likeInfo);

        $this->incrementLikeCount();

        return true;
    }

    /**
     * Remove a like from this record for the given user.
     * @param $authorInfo mixed - If null will use currently logged in user.
     * @return bool
     */
    public function unlike($authorInfo = null)
    {
        $authorInfo = !is_null($authorInfo) ? $authorInfo : Auth::user();
        if (!$authorInfo) return;

        $likeInfo = $this->likes()
            ->where('author_id', $authorInfo->getId())
            ->where('author_type', get_class($authorInfo))
            ->first();

        if (!$likeInfo) return true;

        $likeInfo->delete();
        $this->decrementLikeCount();

        return true;
    }

    /**
     * Has the currently logged in user already "liked" the current object
     *
     * @param $authInfo
     * @return boolean
     */
    public function liked($authInfo = null)
    {
        $authInfo = $authInfo ? $authInfo : Auth::user();

        return (bool)$this->likes()
            ->where('author_id', '=', $authInfo->getId())
            ->where('author_type', '=', get_class($authInfo))
            ->count();
    }

    /**
     * Should remove likes on model row delete (defaults to true)
     * public static removeLikesOnDelete = false;
     */
    public static function removeLikesOnDelete()
    {
        return isset(static::$removeLikesOnDelete)
            ? static::$removeLikesOnDelete
            : true;
    }

    /**
     * Delete likes related to the current record
     */
    public function removeLikes()
    {
        $this->likes()->delete();
        $this->likeCounter()->delete();
    }


    /**
     * Collection of the likes on this record
     * @access private
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Did the currently logged in user like this model
     * Example : if($book->liked) { }
     * @return boolean
     * @access private
     */
    public function getLikedAttribute()
    {
        return $this->liked();
    }

    /**
     * Counter is a record that stores the total likes for the
     * morphed record
     * @access private
     */
    public function likeCounter()
    {
        return $this->morphOne(LikeCounter::class, 'likeable');
    }

    /**
     * Private. Increment the total like count stored in the counter
     */
    private function incrementLikeCount()
    {
        $counter = $this->likeCounter()->first();

        if ($counter) {
            $counter->count++;
            $counter->save();
        } else {
            $counter = new LikeCounter;
            $counter->count = 1;
            $this->likeCounter()->save($counter);
        }
    }

    /**
     * Private. Decrement the total like count stored in the counter
     */
    private function decrementLikeCount()
    {
        $counter = $this->likeCounter()->first();

        if ($counter) {
            $counter->count--;
            if ($counter->count) {
                $counter->save();
            } else {
                $counter->delete();
            }
        }
    }


    /**
     * @param Builder $builder
     * @param null $userId
     * @return mixed
     */
    public function scopeWhereLikedBy(Builder $builder, $authorInfo = null)
    {
        $authorInfo = $authorInfo ? $authorInfo : Auth::user();

        return $builder->whereHas('likes', function ($q) use ($authorInfo) {
            $q
                ->where('author_id', '=', $authorInfo->getId())
                ->where('author_type', '=', get_class($authorInfo));
        });
    }

    /**
     * Fetch the primary ID of the currently logged in user
     * @return mixed
     */
    private function loggedInUserId()
    {
        return auth()->id();
    }
}
