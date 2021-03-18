<?php

namespace App\Modules\Likeable\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Likeable\Resources\CategoryResource;
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
     * Danh sách danh mục đang theo dõi
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $authorInfo = Auth::user();

        $categories = $this->categoryRepo->getFollowedCategories($authorInfo)
            ->select(array('category_id', 'title'))
            ->with('likeCounter')
            ->filter($request->all())
            ->get();

        return CategoryResource::collection($categories);
    }

}
