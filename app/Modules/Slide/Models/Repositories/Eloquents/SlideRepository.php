<?php

/**
 * Slide Eloquent Repository
 *
 * @author xuanhieupd
 * @package Slide
 * @copyright (c) 18.02.2020, HNW
 */

namespace App\Modules\Slide\Models\Repositories\Eloquents;

use App\Base\AbstractRepository;
use App\Modules\Slide\Models\Entities\Slide;
use App\Modules\Slide\Models\Repositories\Contracts\SlideInterface;

class SlideRepository extends AbstractRepository implements SlideInterface
{

    /**
     * Danh sách slides
     *
     * @param array $conditions
     * @param array $fetchOptions
     * @return $this
     * @author xuanhieupd
     */
    public function getAppSlides(array $conditions = array(), array $fetchOptions = array())
    {
        return $this->makeModel()
            ->public();
    }

    /**
     * @return Slide
     */
    public function model()
    {
        return Slide::class;
    }
}
