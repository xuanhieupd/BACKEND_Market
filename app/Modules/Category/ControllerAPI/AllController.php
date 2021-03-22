<?php

namespace App\Modules\Category\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Category\Resources\RecursiveCategoriesResource;
use Illuminate\Http\Request;

class AllController extends AbstractController
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
        $rootInfo = $this->getRoot($request);

        $builder = $this->categoryRepo->makeModel()
            ->select(array('category_id', 'title', 'parent_id', 'level', 'lft', 'rgt'))
            ->where('category_id', '!=', 0)
            ->where('lft', '>', $rootInfo->getAttribute('lft'))
            ->where('rgt', '<', $rootInfo->getAttribute('rgt'));

        $levelFromInput = $request->get('level');
        if ($request->has('level') && is_numeric($levelFromInput)) {
            $builder = $builder->level($levelFromInput);
        }

        $categories = $builder->get();
        return new RecursiveCategoriesResource($categories);
    }

    protected function getRoot(Request $request)
    {
        $parentIdFromInput = $request->get('parent_id');
        $rootInfo = $this->categoryRepo->getCategoryById($parentIdFromInput)->first();

        if (!$rootInfo) {
            return $this->categoryRepo->getCategoryById(0)->first();
        }

        return $rootInfo;
    }

}
