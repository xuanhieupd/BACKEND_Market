<?php

namespace App\Modules\User\Models\Entities;

use App\Base\AbstractModel;
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

}
