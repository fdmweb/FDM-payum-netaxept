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

namespace Tests\TestCase\Request;

use FDM\Payum\Netaxept\Request\GetTerminalUrl;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class GetTerminalUrlTest extends TestCase
{
    public function testTransactionId()
    {
        $request = new GetTerminalUrl('thisisatransactionid');
        self::assertEquals('thisisatransactionid', $request->getTransactionId());
    }

    public function testTerminalUrl()
    {
        $request = new GetTerminalUrl('thisisatransactionid');
        $request->setTerminalUrl(new Uri('http://thisisaurl.com/blah'));
        self::assertEquals('http://thisisaurl.com/blah', $request->getTerminalUrl());
    }
}
