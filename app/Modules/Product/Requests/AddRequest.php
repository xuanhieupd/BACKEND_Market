<?php

namespace App\Modules\Product\Requests;

use App\Base\AbstractRequest;
use App\Base\Requests\VariantsRequestTrait;
use Illuminate\Support\Collection;

class AddRequest extends AbstractRequest
{

    use VariantsRequestTrait;

    /**
     * Rules
     *
     * @return string[]
     * @author xuanhieupd
     */
    public function rules()
    {
        return array_merge(array(
            'sku' => 'required|string',
            'title' => 'required|string',
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'prices' => 'required|array',
            'prices.import' => 'required|numeric|min:0',
            'prices.whole' => 'required|numeric|min:0',
            'prices.retail' => 'required|numeric|min:0',
            'prices.collaborator' => 'required|numeric|min:0',
            'attachment_hash' => 'string',
        ), $this->getVariantsRules());
    }

    /**
     * @return string
     */
    public function getAttachmentHash()
    {
        return $this->get('attachment_hash', '');
    }

}
