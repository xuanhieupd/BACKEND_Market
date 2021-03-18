<?php

namespace App\Modules\Store\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use App\Modules\Store\Resources\FullStoreResource;
use App\Modules\Store\Resources\StoreResource;
use Illuminate\Http\Request;

class StoreController extends AbstractController
{

    /**
     * @var StoreRepository
     */
    private $storeRepo;

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
     * Danh sách cửa hàng
     *
     * @param Request $request
     * @return StoreResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $storeInfo = $request->input('store');

        $storeInfo->loadCount(array(
            'storeProducts',
        ));

        return new FullStoreResource($storeInfo);
    }

}


