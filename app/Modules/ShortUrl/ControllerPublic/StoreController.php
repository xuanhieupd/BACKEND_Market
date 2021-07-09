<?php

namespace App\Modules\ShortUrl\ControllerPublic;

use App\Base\AbstractController;
use App\Modules\ShortUrl\Exceptions\SpecificException;
use App\Modules\ShortUrl\Models\Services\ShortUrlService;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StoreController extends AbstractController
{

    /**
     * @var StoreInterface
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
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        try {
            $data = ShortUrlService::specific($request->route('modelId'));
            $storeInfo = $this->storeRepo->getStoreById($data['modelId'])->first();
            if (!$storeInfo) return abort(404);

            $fullUrl = ShortUrlService::toUrl($data['appId'], strtr('store/:storeId', array(':storeId' => $storeInfo->getId())));
            if (blank($fullUrl)) return abort(404);

            return Redirect::to($fullUrl);
        } catch (SpecificException $e) {
            return abort(404);
        }
    }

}
