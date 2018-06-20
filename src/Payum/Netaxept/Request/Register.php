<?php

declare(strict_types=1);

/*
 * This file is part of the Netaxept Payum Gateway package.
 *
 * (c) Andrew Plank
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDM\Payum\Netaxept\Request;

use Payum\Core\Request\Generic;

class Register extends Generic
{
    /**
     * @var string
     */
    protected $transactionId;

    public function setTransactionId(string $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
