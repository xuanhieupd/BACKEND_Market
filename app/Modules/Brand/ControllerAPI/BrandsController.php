<?php

/**
 * Brands Controller
 *
 * @author xuanhieupd
 * @package Brand
 * @copyright 20.11.2020, HNW
 */

namespace App\Modules\Brand\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Brand\Models\Repositories\Contracts\BrandInterface;
use App\Modules\Brand\Models\Repositories\Eloquents\BrandRepository;
use App\Modules\Brand\Resources\BrandResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandsController extends AbstractController
{

    /**
     * @var BrandRepository
     */
    private $brandRepo;

    /**
     * Constructor.
     *
     * @param BrandInterface $brandRepo
     * @author xuanhieupd
     */
    public function __construct(BrandInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    /**
     * Danh sách thương hiệu
     *
     * @param Request $request
     * @return BrandResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $brands = $this->brandRepo->getStoreBrands($visitor->getStoreId());

        return BrandResource::collection($brands);

    }

}


