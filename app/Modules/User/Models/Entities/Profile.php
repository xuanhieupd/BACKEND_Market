<?php

namespace App\Modules\User\Models\Entities;

use App\Base\AbstractModel;
use App\Helper;
use App\Modules\Base\Helpers\FileHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Profile extends AbstractModel
{

    protected $connection = 'box';
    protected $table = 'hnw_user_profile';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = array(
        'user_id',
        'dob_day',
        'dob_month',
        'dob_year',
        'gender',
        'age',
        'telephone',
        'job',
        'about',
        'address',
        'city',
        'country',
        'dob',
        'avatar',
        'background_image'
    );

    /**
     * @return int
     * @author xuanhieupd
     */
    public function getId()
    {
        return $this->getAttribute('user_id');
    }

    /**
     * @param Builder $builder
     * @param $phoneNumber
     * @return Builder
     * @author xuanhieupd
     */
    public function scopePhoneNumber(Builder $builder, $phoneNumber)
    {
        return $builder->where('telephone', $phoneNumber);
    }

    /**
     * @return mixed
     * @copyright (c) 5:13 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public function getAvatarUrlAttribute()
    {
        $imageOriginal = $this->getOriginal('avatar');

        return $imageOriginal ? FileHelper::getFileUrl($imageOriginal) : 'http://via.placeholder.com/150/385898/385898';
    }

    /**
     * @return mixed
     * @copyright (c) 5:13 PM, Julyboy
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     */
    public function getBackgroundUrlAttribute()
    {
        $imageOriginal = $this->getOriginal('background_image');

        return FileHelper::getFileUrl($imageOriginal);
    }

    /**
     * @return string
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     * @copyright (c) 29/6/2021
     */
    public function getDobFormatAttribute()
    {
        $value = $this->getAttribute('dob');
        if(!$value) return '';
        if(!$value instanceof Carbon)
            $value = Carbon::createFromFormat('Y-m-d', $value);
        return $value->format('d-m-Y');
    }

    /**
     * @return array
     * @author Julyboy <cntt0401.luuvietduc@gmail.com>
     * @copyright (c) 27/6/2021
     */
    public function toResource()
    {

        return array(
            'dob' => $this->getDobFormatAttribute(),
            'avatar' => $this->getAttribute('avatar_url'),
            'background_image' => $this->getAttribute('background_url'),
            'phone_number' => $this->getAttribute('telephone'),
        );
    }

}
