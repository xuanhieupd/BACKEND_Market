<?php

namespace Database\Seeders;

use App\Libraries\Chat\Facades\ChatFacade;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use App\Modules\User\Models\Entities\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{

    /**
     * @var StoreRepository
     */
    protected $storeRepo;

    /**
     * Constructor.
     *
     * @param StoreInterface $storeRepo
     * @author xuanhieupd
     */
    public function __construct(StoreInterface $storeRepo)
    {
        $this->storeRepo = $storeRepo;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = $this->storeRepo->getStores()->limit(20)->get();
        $userInfo = User::query()->first();

        foreach ($stores as $storeInfo) {
            $participants = [$userInfo, $storeInfo,];
            ChatFacade::createConversation($participants);
        }
    }
}
