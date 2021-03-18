<?php
/**
 * Stores Controller
 *
 * @author xuanhieupd
 * @package Store
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Store\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use App\Modules\Store\Resources\StoreWithProductCountResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class StoresController extends AbstractController
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
     * @return StoreWithProductCountResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $currentPage = $request->get('page');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $stores = $this->storeRepo->getStores()
            ->search($request->get('q'))
            ->filter($request->all())
            ->withCount('storeProducts')
            ->simplePaginate(10);

        return StoreWithProductCountResource::collection($stores);
    }

}


