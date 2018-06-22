<?php

/*
 * This file is part of the Netaxept Payum Gateway package.
 *
 * (c) Andrew Plank
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\TestCase\Model;

use FDM\Payum\Netaxept\Model\Payment;
use FDM\Payum\Netaxept\Model\TransactionInterface;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testModel()
    {
        $model = new Payment();
        self::assertInstanceOf(TransactionInterface::class, $model, 'Model does not implement TransactionInterface');
        $model->setTransactionId('123456');
        self::assertEquals('123456', $model->getTransactionId());
    }
}
