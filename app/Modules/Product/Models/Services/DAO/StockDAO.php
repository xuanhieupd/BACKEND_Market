<?php
/**
 * Stock DAO
 *
 * @author xuanhieupd
 * @package Product
 * @copyright 04.10.2020, HNW
 */

namespace App\Modules\Product\Models\Services\DAO;

use App\Modules\Product\Models\Services\Contracts\AlterStockInterface;

class StockDAO
{

    /**
     * @var int
     */
    private $stock;

    /**
     * @var AlterStockInterface
     */
    private $holder;

    /**
     * StockDAO constructor.
     *
     * @param AlterStockInterface $holder
     * @param int $stock
     * @author xuanhieupd
     */
    public function __construct(AlterStockInterface $holder, int $stock)
    {
        $this->holder = $holder;
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return AlterStockInterface
     */
    public function getHolder(): AlterStockInterface
    {
        return $this->holder;
    }

    /**
     * @param AlterStockInterface $holder
     */
    public function setHolder(AlterStockInterface $holder): void
    {
        $this->holder = $holder;
    }


}
