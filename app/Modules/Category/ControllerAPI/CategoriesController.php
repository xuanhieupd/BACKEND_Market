<?php

namespace App\Modules\Category\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Category\Resources\RecursiveCategoriesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends AbstractController
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    /**
     * Constructor.
     *
     * @param CategoryInterface $categoryRepo
     * @author xuanhieupd
     */
    public function __construct(CategoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Danh sách danh mục
     *
     * @param Request $request
     * @return RecursiveCategoriesResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $categories = $this->categoryRepo->makeModel()
            ->select(array('category_id', 'title', 'parent_id'))
            ->filter($request->all())
            ->get();

        return CategoryResource::collection($categories);
    }

}
