<?php

namespace App\Modules\Feed\Controllers\Requests;

use App\Base\AbstractRequest;

class SaveRequest extends AbstractRequest
{

    /**
     * @return string[]
     */
    public function rules()
    {
        return array(
            'title' => 'string',
            'description' => 'required|string',
            'attachment_ids' => 'required|array',
            'attachment_ids.*' => 'numeric',
            'product_ids' => 'array',
            'product_ids.*' => 'numeric',
        );
    }

    /**
     * @return array
     */
    public function getAttachmentIds()
    {
        return $this->get('attachment_ids', array());
    }

    /**
     * @return array
     */
    public function getProductIds()
    {
        return $this->get('product_ids', array());
    }

}
