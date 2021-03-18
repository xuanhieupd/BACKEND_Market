<?php

namespace App\Modules\Product\Resources;

use App\Base\AbstractResource;
use Illuminate\Http\Request;

class SearchResource extends AbstractResource
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
            'product_id' => $this->getId(),
            'sku' => $this->getSku(),
            'title' => $this->getAttribute('title'),
            'current_stock' => $this->getCurrentStock(),
            'total_stock' => $this->getTotalStock(),
            'thumb_url' => $this->getThumbUrl(),
            'prices' => array(
                'import' => $this->getImportPrice(),
                'whole' => $this->getWholePrice(),
                'retail' => $this->getRetailPrice(),
                'collaborator' => $this->getCollaboratorPrice(),
            )
        );
    }

}
