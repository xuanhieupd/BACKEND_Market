<?php

namespace App\Libraries\Chat\Commanding;

interface CommandHandler
{
    public function handle($command);
}
