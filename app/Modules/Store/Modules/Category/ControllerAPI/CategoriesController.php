<?php

namespace App\Modules\Store\Modules\Category\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Base\Helpers\CollectionHelper;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use Illuminate\Http\Request;

class CategoriesController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepo;

    /**
     * Constructor.
     *
     * @param ProductInterface $productRepo
     * @author xuanhieupd
     */
    public function __construct(ProductInterface $productRepo, CategoryInterface $categoryRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Danh sách danh mục mà cửa hàng này có sản phẩm
     *
     * @param Request $request
     * @return CategoryResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $products = $this->productRepo->getProducts()
            ->select(array('category_id'))
            ->groupBy('category_id')
            ->get();

        $categories = $this->categoryRepo->getCategories()
            ->whereIn('category_id', CollectionHelper::pluckUnique($products, 'category_id')->toArray())
            ->select(array('category_id', 'title'))
            ->level(2)
            ->get();

        return CategoryResource::collection($categories);
    }

}
