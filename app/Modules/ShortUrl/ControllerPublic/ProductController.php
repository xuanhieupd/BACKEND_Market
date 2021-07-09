<?php

namespace App\Modules\ShortUrl\ControllerPublic;

use App\Base\AbstractController;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\ShortUrl\Exceptions\SpecificException;
use App\Modules\ShortUrl\Models\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductController extends AbstractController
{

    /**
     * @var ProductInterface
     */
    protected $productRepo;

    /**
     * Constructor.
     *
     * @param ProductInterface $productRepo
     * @author xuanhieupd
     */
    public function __construct(ProductInterface $productRepo)
    {
        $this->productRepo = $productRepo;
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
            $productInfo = $this->productRepo->getProductById($data['modelId'])->first();
            if (!$productInfo) return abort(404);

            $fullUrl = ShortUrlService::toUrl($data['appId'], strtr('product/:productId', array(':productId' => $productInfo->getId())));
            if (blank($fullUrl)) return abort(404);

            return Redirect::to($fullUrl);
        } catch (SpecificException $e) {
            return abort(404);
        }
    }

}
