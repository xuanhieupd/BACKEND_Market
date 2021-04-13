<?php

namespace App\Modules\Notification\Models\Repositories\Contracts;

use App\Modules\Notification\Models\Entities\Token;

interface TokenInterface
{

    public function store(Token $tokenInfo);

}
