<?php

/**
 * Slide Modal
 *
 * @author xuanhieupd
 * @package Slide
 * @copyright (c) 18.02.2020, HNW
 */

namespace App\Modules\Slide\Models\Entities;

use App\Base\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Slide extends AbstractModel
{

    protected $table = 'hnw_slide';
    protected $primaryKey = 'slide_id';
    public $timestamps = true;
    public static $tableAlias = 'hnw_slide';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'image',
        'url',
        'status'
    );

    /**
     * Alias for `slide_id`
     *
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('slide_id');
    }

    /**
     * Ảnh đại diện của cửa hàng
     *
     * @return string
     * @author xuanhieupd
     */
    public function getImageUrlAttribute()
    {
        $imageValue = $this->getAttribute('image');
        return (!filter_var($imageValue, FILTER_VALIDATE_URL)) ?
            Storage::disk('cdn')->url($imageValue) :
            $imageValue;
    }

    /**
     * Lấy đường dẫn
     *
     * @return string
     * @author xuanhieupd
     */
    public function getDataUrlAttribute()
    {
        return '';
    }

    /**
     * Lọc trạng thái hiển thị / không hiện thị
     *
     * @param Builder $builder
     * @return Builder
     * @author xuanhieupd
     */
    public function scopePublic(Builder $builder)
    {
        return $builder;
    }


}
