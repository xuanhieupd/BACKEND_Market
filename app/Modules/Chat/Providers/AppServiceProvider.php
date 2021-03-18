<?php

namespace App\Modules\Chat\Providers;

use App\Modules\Chat\Models\Repositories\Contracts\ConversationInterface;
use App\Modules\Chat\Models\Repositories\Contracts\MessageInterface;
use App\Modules\Chat\Models\Repositories\Contracts\ParticipationInterface;
use App\Modules\Chat\Models\Repositories\ConversationRepository;
use App\Modules\Chat\Models\Repositories\MessageRepository;
use App\Modules\Chat\Models\Repositories\ParticipationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Boot
     *
     * @return void
     * @author xuanhieupd
     */
    public function boot()
    {
        $this->app->singleton(ConversationInterface::class, ConversationRepository::class);
        $this->app->singleton(MessageInterface::class, MessageRepository::class);
        $this->app->singleton(ParticipationInterface::class, ParticipationRepository::class);
    }

}
