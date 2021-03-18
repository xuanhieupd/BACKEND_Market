<?php

/**
 * Change Quantity Request
 *
 * @author xuanhieupd
 * @package Order
 * @copyright 08.10.2020, HNW
 */

namespace App\Modules\Order\Requests\Change;

use App\Base\AbstractRequest;
use Illuminate\Support\Collection;

class QuantityRequest extends AbstractRequest
{
    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'datas' => 'required|array',
            'datas.*.variant_id' => 'required|numeric',
            'datas.*.quantity' => 'required|numeric',
        );
    }

    /**
     * Pluck `variant_id`
     *
     * @return Collection
     * @author xuanhieupd
     */
    public function getVariantIds()
    {
        $datasFromInput = $this->get('datas');
        return collect($datasFromInput)->pluck('variant_id')->values()->toArray();
    }

    /**
     * Lấy số lượng thay đổi
     *
     * @param $variantId
     * @return int
     * @author xuanhieupd
     */
    public function getQuantity($variantId)
    {
        $dataInfo = $this->getDataInfo($variantId);
        return data_get($dataInfo, 'quantity', 0);
    }

    /**
     * Check có bản ghi cập nhật số lượng hay ko ?
     *
     * @param $variantId
     * @return bool
     * @author xuanhieupd
     */
    public function hasVariant($variantId)
    {
        $dataInfo = $this->getDataInfo($variantId);
        return !is_null($dataInfo);
    }

    /**
     * Rows by variantId
     *
     * @param $variantId
     * @return array|null
     * @author xuanhieupd
     */
    public function getDataInfo($variantId)
    {
        $datasFromInput = $this->get('datas');
        return collect($datasFromInput)->where('variant_id', $variantId)->first();
    }

}
