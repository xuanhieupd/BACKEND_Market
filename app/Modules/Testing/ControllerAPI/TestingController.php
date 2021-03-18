<?php

namespace App\Modules\Testing\ControllerAPI;

use App\Base\AbstractController;
use App\Libraries\Chat\Facades\ChatFacade;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\BillReturn\Modules\StoreToSupplier\Models\Entities\StoreToSupplier;
use App\Modules\Category\Models\Entities\Category;
use App\Modules\Customer\Models\Entities\Customer;
use App\Modules\Customer\Modules\Address\Models\Entities\CustomerAddress;
use App\Modules\Customer\Modules\PhoneNumber\Models\Entities\CustomerPhone;
use App\Modules\Product\Modules\Color\Models\Entities\Color;
use App\Modules\Product\Modules\Size\Models\Entities\Size;
use App\Modules\Store\Models\Entities\Store;
use App\Modules\Testing\Models\Entities\Test;
use App\Modules\User\Models\Entities\User;
use App\Modules\User\Models\Repositories\Contracts\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NestedSet;
use App\Libraries\Chat\Facades\ChatFacade as Chat;


class TestingController extends AbstractController
{

    public function actionIndex(Request $request)
    {
        $conversation = ChatFacade::conversations()->getById(1);
        $storeInfo = Store::query()->where('store_id', 1)->first();
        $userInfo = User::query()->first();

        $message = Chat::message(array('asdasd'))
            ->from($userInfo)
            ->to($conversation)
            ->send();

        die;

//        $storeInfo = Store::query()->where('store_id', 1)->first();

//        $conversations = Chat::conversations()->setParticipant($userInfo)->get();

        $messages = Chat::conversation($conversation)->setParticipant($userInfo)->getMessages();

//        $paginated = Chat::conversations()->setParticipant($userInfo)
//            ->setPaginationParams([
//                'page' => 3,
//                'perPage' => 10,
//                'sorting' => "desc",
//                'columns' => [
//                    '*'
//                ],
//                'pageName' => 'test'
//            ])
//            ->get();

        print_r($messages->toArray());
        die;


//        $message = Chat::message('Hello')
//            ->from($userInfo)
//            ->to($conversation)
//            ->send();

        print_r($conversation->messages->toArray());

        die;


        $conversation = ChatFacade::conversations()->getById(1);

        $participants = [$storeInfo, $userInfo];
        $conversation = ChatFacade::createConversation($participants);

        print_r($conversation->toArray());
        die;

        $users = User::query()->get();

        foreach ($users as $userInfo) {
            $storeInfo->like($userInfo->getId());
        }

        die;

        $categoryInfo = Category::query()->find(1);
        $categoryInfo->like(1);
        die('ádad');

        $faker = \Faker\Factory::create();
//        echo $faker->e164PhoneNumber;die;
//
////        echo implode('_', array(
////            $faker->name(),
////            $faker->phoneNumber,
////            $faker->address
////        ));
////        die;

        for ($i = 1; $i <= 3; $i++) {
            $customerInfo = new Customer(array(
                'store_id' => 1,
                'group_id' => 1,
                'user_id' => 1,
                'code' => Str::random(3),
                'fullname' => $faker->name(),
                'rate_number' => 0
            ));

            $customerInfo->save();

            $address = new CustomerAddress(array(
                'customer_id' => $customerInfo->getId(),
                'user_id' => 1,
                'address' => $faker->address,
                'is_primary' => 1,
            ));

            $address->save();

            $phone = new CustomerPhone(array(
                'customer_id' => $customerInfo->getId(),
                'user_id' => 1,
                'phone_number' => trim($faker->e164PhoneNumber, '+'),
                'is_primary' => 1,
            ));

            $phone->save();
        }

        die;


        Category::fixTree();
        die;
        Color::fixTree();
        Size::fixTree();
        die;
        $attachment = Attachment::query()->orderBy('attachment_id', 'DESC')->first();
        echo $attachment->getThumbnailUrl();
        die;

        $datas = StoreToSupplier::query()->get();

        print_r($datas->toArray());
        die;


        die;
        User::fixTree();
        die;
        $user = User::find(1);

        $users = app(UserInterface::class)->getStoreSupervisorUsers(1, $user);

        return $users->toTree();

    }

}
