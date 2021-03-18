<?php

namespace App\Base\Requests;

use Illuminate\Support\Collection;

trait VariantsRequestTrait
{

    /**
     * Rules
     *
     * @return string[]
     * @author xuanhieupd
     */
    public function getVariantsRules()
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
     * Lấy số lượng
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

    /**
     * @return Collection
     */
    public function getDatas()
    {
        return collect($this->get('datas', array()));
    }

    /**
     * Tổng số lượng
     *
     * @return mixed
     * @author xuanhieupd
     */
    public function getTotalQuantity()
    {
        return $this->getDatas()->sum('quantity');
    }

    /**
     * Tổng tiền
     *
     * @return int
     * @author xuanhieupd
     */
    public function getTotalPrice()
    {
        $totalPrice = 0;

        foreach ($this->getDatas() as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return $totalPrice;
    }

}
