<?php

namespace App\Modules\Product\Middleware;

use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use Illuminate\Http\Request;
use Closure;

class ProductMiddleware
{
    /**
     * @var ProductRepository
     */
    private $productRepo;

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
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $productId = $request->route('productId', -1);

        $productInfo = $this->productRepo->getProductById($productId)->first();
        if (!$productInfo) return response()->responseError('Không tìm thấy thông tin sản phẩm');

        $request->merge(array('product' => $productInfo));
        return $next($request);
    }
}
