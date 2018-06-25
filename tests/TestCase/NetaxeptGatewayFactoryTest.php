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

namespace Tests\TestCase\Model;

use FDM\Payum\Netaxept\NetaxeptGatewayFactory;
use FDM\Payum\Netaxept\Request\GetTerminalUrl;
use Payum\Core\Gateway;
use PHPUnit\Framework\TestCase;

class NetaxeptGatewayFactoryTest extends TestCase
{
    public function testFac()
    {
        $gateway = (new NetaxeptGatewayFactory())->create([
            'merchantId' => 'merchant',
            'token' => 'token',
            'sandbox' => true,
        ]);

        self::assertInstanceOf(Gateway::class, $gateway);
        $gateway->execute($request = new GetTerminalUrl('transactionid'));
        self::assertEquals('https://test.epayment.nets.eu/Terminal/Default.aspx?merchantId=merchant&' .
            'transactionId=transactionid', $request->getTerminalUrl());
    }
}
