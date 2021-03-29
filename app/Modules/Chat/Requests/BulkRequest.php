<?php

namespace App\Modules\Chat\Requests;

use App\Base\AbstractRequest;
use App\Modules\Chat\DAO\TargetDAO;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use Illuminate\Support\Collection;

class BulkRequest extends AbstractRequest
{

    /**
     * @return string[]
     */
    public function rules()
    {
        return array(
            'message' => 'required|string',
            'product_ids' => 'array',
            'product_ids.*' => 'required|numeric',
            'attachment_ids' => 'array',
            'attachment_ids.*' => 'required|numeric',
            'target' => 'required',
            'target_ids' => 'required|array',
        );
    }

    /**
     * @return TargetDAO
     */
    public function getTarget()
    {
        $targetDao = new TargetDAO();
        $targetDao->setTarget($this->get('target'));
        $targetDao->setTargetIds($this->get('target_ids'));

        return $targetDao;
    }

    /**
     * @return array
     */
    public function getProductIds()
    {
        return $this->get('product_ids', array());
    }

    /**
     * @return array
     */
    public function getAttachmentIds()
    {
        return $this->get('attachment_ids', array());
    }
}
