<?php


namespace App\Modules\Likeable\Models\Entities;

use App\Base\AbstractModel;
use Conner\Likeable\Like;

class LikeCounter extends AbstractModel
{

    protected $table = 'hnw_likeable_like_counter';
    public $timestamps = false;
    protected $fillable = ['likeable_id', 'likeable_type', 'count'];

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
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Delete all counts of the given model, and recount them and insert new counts
     *
     * @param $modelClass
     */
    public static function rebuild($modelClass)
    {
        if (empty($modelClass)) {
            throw new \Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
        }

        $builder = Like::query()
            ->select(\DB::raw('count(*) as count, likeable_type, likeable_id'))
            ->where('likeable_type', $modelClass)
            ->groupBy('likeable_id');

        $results = $builder->get();

        $inserts = $results->toArray();

        \DB::table((new static)->table)->insert($inserts);
    }
}
