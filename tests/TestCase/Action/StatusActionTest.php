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

use FDM\Payum\Netaxept\Action\StatusAction;
use FDM\Payum\Netaxept\Request\GetStatus;
use Payum\Core\Request\Cancel;
use PHPUnit\Framework\Assert;

class StatusActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $statusAction = new StatusAction();
        $request = new Cancel([]);

        Assert::assertFalse($statusAction->supports($request));
    }

    public function testSupportsFailsWithCorrectRequestButIncorrectModel()
    {
        $statusAction = new StatusAction();
        $request = new GetStatus(new \stdClass());

        Assert::assertFalse($statusAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $statusAction = new StatusAction();
        $request = new GetStatus([]);

        Assert::assertTrue($statusAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action StatusAction is not supported the request Cancel{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $statusAction = new StatusAction();
        $request = new Cancel([]);

        $statusAction->execute($request);
    }

    public function testRequestMarkedAsNewWithEmptyModel()
    {
        $statusAction = new StatusAction();
        $request = new GetStatus([]);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isNew());
    }

    public function testRequestMarkedAsNewWhenInvalidTransactionId()
    {
        $api = $this->getInstanceForRequestFixture('responses/status/invalid_transaction_id.xml');
        $statusAction = new StatusAction();
        $statusAction->setApi($api);
        $request = new GetStatus(['transactionId' => 'fred']);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isNew());
    }

    public function testRequestMarkedAsPending()
    {
        $api = $this->getInstanceForRequestFixture('responses/status/registered.xml');
        $statusAction = new StatusAction();
        $statusAction->setApi($api);
        $request = new GetStatus(['transactionId' => 'fred']);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isPending());
    }

    public function testRequestMarkedAsAuthorized()
    {
        $api = $this->getInstanceForRequestFixture('responses/status/authorized.xml');
        $statusAction = new StatusAction();
        $statusAction->setApi($api);
        $request = new GetStatus(['transactionId' => 'fred']);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isAuthorized());
    }

    public function testRequestMarkedAsCaptured()
    {
        $api = $this->getInstanceForRequestFixture('responses/status/captured.xml');
        $statusAction = new StatusAction();
        $statusAction->setApi($api);
        $request = new GetStatus(['transactionId' => 'fred']);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isCaptured());
    }

    public function testRequestMarkedAsRefunded()
    {
        $api = $this->getInstanceForRequestFixture('responses/status/refunded.xml');
        $statusAction = new StatusAction();
        $statusAction->setApi($api);
        $request = new GetStatus(['transactionId' => 'fred']);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isRefunded());
    }

    public function testRequestMarkedAsCancelled()
    {
        $api = $this->getInstanceForRequestFixture('responses/status/cancelled.xml');
        $statusAction = new StatusAction();
        $statusAction->setApi($api);
        $request = new GetStatus(['transactionId' => 'fred']);

        Assert::assertTrue($request->isUnknown());
        $statusAction->execute($request);
        Assert::assertTrue($request->isCanceled());
    }
}
