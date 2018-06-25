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

use FDM\Payum\Netaxept\Request\Register;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    public function testTransactionId()
    {
        $request = new Register([]);
        $request->setTransactionId('thisisatransactionid');
        self::assertEquals('thisisatransactionid', $request->getTransactionId());
    }
}
