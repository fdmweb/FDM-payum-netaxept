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

namespace Tests\TestCase\Action;

use FDM\Payum\Netaxept\Action\GetTerminalUrlAction;
use FDM\Payum\Netaxept\Request\GetTerminalUrl;
use Payum\Core\Request\Cancel;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\Assert;

class GetTerminalUrlActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $terminalAction = new GetTerminalUrlAction();
        $request = new Capture(new \stdClass());

        Assert::assertFalse($terminalAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $terminalAction = new GetTerminalUrlAction();
        $terminalAction->setApi($api);
        $request = new GetTerminalUrl('thisisatransactionid');

        Assert::assertTrue($terminalAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action GetTerminalUrlAction is not supported the request Cancel{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $terminalAction = new GetTerminalUrlAction();
        $terminalAction->setApi($api);
        $request = new Cancel([]);

        $terminalAction->execute($request);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Transaction ID empty.
     */
    public function testExecuteFailsCorrectRequestButNoTransactionId()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $terminalAction = new GetTerminalUrlAction();
        $terminalAction->setApi($api);
        $request = new GetTerminalUrl('');

        $terminalAction->execute($request);
    }

    public function testExecuteSucceedsAsExpected()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $terminalAction = new GetTerminalUrlAction();
        $terminalAction->setApi($api);
        $request = new GetTerminalUrl('thisisthetransactionid');

        $terminalAction->execute($request);

        Assert::assertEquals('https://test.epayment.nets.eu/Terminal/Default.aspx?merchantId=placeholdermerchant&' .
            'transactionId=thisisthetransactionid', $request->getTerminalUrl());
    }
}
