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

use FDM\Payum\Netaxept\Action\RefundAction;
use Payum\Core\Request\Cancel;
use Payum\Core\Request\Refund;
use PHPUnit\Framework\Assert;

class RefundActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $refundAction = new RefundAction();
        $refundAction->setApi($api);
        $request = new Cancel([]);

        Assert::assertFalse($refundAction->supports($request));
    }

    public function testSupportsFailsWithCorrectRequestButIncorrectModel()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $refundAction = new RefundAction();
        $refundAction->setApi($api);
        $request = new Refund(new \stdClass());

        Assert::assertFalse($refundAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $refundAction = new RefundAction();
        $refundAction->setApi($api);
        $request = new Refund([]);

        Assert::assertTrue($refundAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action RefundAction is not supported the request Cancel{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $refundAction = new RefundAction();
        $refundAction->setApi($api);
        $request = new Cancel([]);

        $refundAction->execute($request);
    }

    public function testExecuteSucceeds()
    {
        $api = $this->getInstanceForRequestFixture('responses/credit.xml');
        $refundAction = new RefundAction();
        $refundAction->setApi($api);
        $request = new Refund([]);

        $response = $refundAction->execute($request);
        Assert::assertEquals('CREDIT', $response->getOperation());
        Assert::assertEquals('OK', $response->getStatus());
    }
}
