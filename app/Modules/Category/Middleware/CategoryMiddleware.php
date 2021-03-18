<?php

namespace App\Modules\Category\Middleware;

use App\Modules\Category\Models\Repositories\Contracts\CategoryInterface;
use App\Modules\Category\Models\Repositories\Eloquents\CategoryRepository;
use App\Modules\Store\Models\Repositories\Contracts\StoreInterface;
use App\Modules\Store\Models\Repositories\Eloquents\StoreRepository;
use Illuminate\Http\Request;
use Closure;

class CategoryMiddleware
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
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $categoryId = $request->route('categoryId', -1);

        $categoryInfo = $this->categoryRepo->getCategoryById($categoryId)->first();
        if (!$categoryInfo) return response()->responseError('Không tìm thấy thông tin danh mục');

        $request->merge(array('category' => $categoryInfo));
        return $next($request);
    }
}
