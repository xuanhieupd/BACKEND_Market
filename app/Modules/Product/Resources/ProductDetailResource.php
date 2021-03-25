<?php

namespace App\Modules\Product\Resources;

use App\Base\AbstractResource;
use App\Modules\Attachment\Resources\AttachmentResource;
use App\Modules\Store\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailResource extends AbstractResource
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
        $attachmentInfo = $this->productAttachments ? $this->productAttachments->first() : null;

        return array(
            'product_id' => $this->getId(),
            'sku' => $this->getSku(),
            'title' => $this->getAttribute('title'),
            'current_stock' => $this->getCurrentStock(),
            'total_stock' => $this->getTotalStock(),
            'price' => $this->getMarketPrice(),
            'prices' => array(
                'import' => $this->getImportPrice(),
                'whole' => $this->getWholePrice(),
                'retail' => $this->getRetailPrice(),
                'collaborator' => $this->getCollaboratorPrice(),
            ),
            'store' => new StoreResource($this->productStore),
            'thumb_url' => $attachmentInfo ? $attachmentInfo->getThumbnailUrlAttribute() : null,
            'attachments' => AttachmentResource::collection($this->productAttachments),
            'datas' => new VariantResource($this->productVariants),
            'like_count' => $this->getLikeCountAttribute(),
            'is_liked' => $this->liked(Auth::user()),
            'display_id' => $this->getAttribute('displayId'),
            'can_view_price' => !!$this->getAttribute('canViewPrice'),
            'can_view_quantity' => !!$this->getAttribute('canViewQuantity'),
        );
    }

}
