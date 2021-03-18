<?php

namespace App\Modules\Product\Modules\Color\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Product\Modules\Color\Models\Repositories\Contracts\ColorInterface;
use App\Modules\Product\Modules\Color\Models\Repositories\Eloquents\ColorRepository;
use App\Modules\Product\Modules\Color\Resources\ColorsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorsController extends AbstractController
{

    /**
     * @var ColorRepository
     */
    private $colorRepo;

    /**
     * Constructor.
     *
     * @param ColorInterface $colorRepo
     * @author xuanhieupd
     */
    public function __construct(ColorInterface $colorRepo)
    {
        $this->colorRepo = $colorRepo;
    }

    /**
     * Danh sách màu
     *
     * @param Request $request
     * @return ColorsResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $colors = $this->colorRepo->getStoreColors($visitor->getStoreId(), array(), array(
            'fields' => array(
                'color_id',
                'hex_color',
                'title',
                'parent_id',
            )
        ));

        return new ColorsResource($colors);
    }

}
