<?php


namespace App\Modules\Order\Resources;


use App\Base\AbstractResource;
use Illuminate\Http\Request;

class VariantColorResource extends AbstractResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @author xuanhieupd
     */
    public function toArray($request)
    {
        return array(
            'color_id' => $this->getId(),
            'title' => $this->getName(),
        );
    }
}
