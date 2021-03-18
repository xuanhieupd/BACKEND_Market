<?php

namespace App\Modules\Product\Modules\Size\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Product\Modules\Color\Resources\ColorsResource;
use App\Modules\Product\Modules\Size\Models\Repositories\Contracts\SizeInterface;
use App\Modules\Product\Modules\Size\Models\Repositories\Eloquents\SizeRepository;
use App\Modules\Product\Modules\Size\Resources\SizesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SizesController extends AbstractController
{

    /**
     * @var SizeRepository
     */
    private $sizeRepo;

    /**
     * Constructor.
     *
     * @param SizeInterface $sizeRepo
     * @author xuanhieupd
     */
    public function __construct(SizeInterface $sizeRepo)
    {
        $this->sizeRepo = $sizeRepo;
    }

    /**
     * Danh sách sizes
     *
     * @param Request $request
     * @return SizesResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $sizes = $this->sizeRepo->getStoreSizes($visitor->getStoreId(), array(), array(
            'fields' => array(
                'size_id',
                'title',
                'parent_id',
            )
        ));

        return new SizesResource($sizes);
    }

}
