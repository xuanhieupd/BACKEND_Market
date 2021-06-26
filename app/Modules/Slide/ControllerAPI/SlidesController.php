<?php

/**
 * Slide Controller
 *
 * @author xuanhieupd
 * @package Slide
 * @copyright (c) 18.02.2020, HNW
 */

namespace App\Modules\Slide\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Slide\Resources\SlideResource;
use App\Modules\Slide\Models\Repositories\Contracts\SlideInterface;
use Illuminate\Http\Request;

class SlidesController extends AbstractController
{

    protected $slideRepo;

    /**
     * Constructor.
     *
     * @param SlideInterface $slProfileResourceideRepo
     * @author xuanhieupd
     */
    public function __construct(SlideInterface $slideRepo)
    {
        $this->slideRepo = $slideRepo;
    }

    /**
     * Danh sách slides
     *
     * @param Request $request
     * @return SlideResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $slides = $this->slideRepo->getAppSlides()->get();

        return SlideResource::collection($slides);
    }

}
