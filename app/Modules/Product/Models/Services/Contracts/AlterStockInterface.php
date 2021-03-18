<?php


namespace App\Modules\Product\Models\Services\Contracts;


interface AlterStockInterface
{
    public function getId();

    public function getAlterStockValue();

    public function getVariantId();

}
