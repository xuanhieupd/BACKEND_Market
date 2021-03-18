<?php

namespace App\Modules\Sanctum\Models\Entities;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class Token extends SanctumPersonalAccessToken
{

    protected $connection = 'mysql';
    protected $table = 'hnw_personal_access_token';

}
