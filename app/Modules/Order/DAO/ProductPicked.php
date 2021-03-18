<?php

namespace App\Modules\Order\DAO;

use App\Modules\Product\Modules\Variant\Models\Entities\Variant;

class ProductPicked
{
    /**
     * @var Variant
     */
    private $variant;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var int
     */
    private $price;

    /**
     * @return Variant
     */
    public function getVariant(): Variant
    {
        return $this->variant;
    }

    /**
     * @param Variant $variant
     */
    public function setVariant(Variant $variant): void
    {
        $this->variant = $variant;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }


}
