<?php

/**
 * Transaction Resource
 *
 * @author xuanhieupd
 * @package Wallet
 * @copyright 06.10.2020
 */

namespace App\Modules\Wallet\Resources;

use App\Base\AbstractResource;

class TransactionResource extends AbstractResource
{
    public function toArray($request)
    {
        return array(
            'id' => $this->id,
            'uuid' => $this->getAttribute('uuid'),
            'metadata' => $this->getAttribute('meta'),
            'type' => $this->type,
            'amount' => $this->amount,
            'message' => '',
            'date' => $this->created_at->toDatetimeString(),
        );
    }
}

