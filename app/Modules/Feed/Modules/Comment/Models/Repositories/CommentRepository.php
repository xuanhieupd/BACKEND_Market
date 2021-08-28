<?php

/**
 * Comment Eloquent Repository
 *
 * @author shin_conan <xuanhieu.pd@gmail.com>
 * @package Comment
 * @copyright (c) 08.10.2020, HNW
 */

namespace App\Modules\Feed\Modules\Comment\Models\Repositories;

use App\Base\AbstractModel;
use App\Base\AbstractRepository;
use App\Modules\Feed\Modules\Comment\Models\Repositories\Contracts\CommentInterface;
use App\Modules\Feed\Modules\Comment\Models\Entities\Comment;

class CommentRepository extends AbstractRepository implements CommentInterface
{

    /**
     * Danh sách bình luận trong bài tin
     *
     * @param $feedId
     * @param array $conditions
     * @param array $fetchOptions
     * @return mixed
     */
    public function getCommentsByFeedId($feedId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('feed_id', $feedId)
            ->orderBy('feed_id', 'DESC');
    }


    /**
     * Lấy comment by Id
     *
     * @param  $author
     * @param $commentId
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getAuthorCommentById($author, $commentId, array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->conditions($conditions)
            ->options($fetchOptions)
            ->where('author_type', get_class($author))
            ->where('author_id', $author->getId())
            ->where('comment_id', $commentId);
    }

    public function model()
    {
        return Comment::class;
    }

}
