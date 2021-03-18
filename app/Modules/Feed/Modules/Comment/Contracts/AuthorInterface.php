<?php

namespace App\Modules\Feed\Modules\Comment\Contracts;

interface AuthorInterface
{

    public function getId();

    public function getFullName();

    public function getAvatarUrl();

}
