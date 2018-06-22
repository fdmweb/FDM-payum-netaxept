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

use FDM\Payum\Netaxept\Action\CancelAction;
use Payum\Core\Request\Cancel;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\Assert;

class CancelActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $cancelAction = new CancelAction();
        $cancelAction->setApi($api);
        $request = new Capture([]);

        Assert::assertFalse($cancelAction->supports($request));
    }

    public function testSupportsFailsWithCorrectRequestButIncorrectModel()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $cancelAction = new CancelAction();
        $cancelAction->setApi($api);
        $request = new Cancel(new \stdClass());

        Assert::assertFalse($cancelAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $cancelAction = new CancelAction();
        $cancelAction->setApi($api);
        $request = new Cancel([]);

        Assert::assertTrue($cancelAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action CancelAction is not supported the request Capture{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $cancelAction = new CancelAction();
        $cancelAction->setApi($api);
        $request = new Capture([]);

        $cancelAction->execute($request);
    }

    public function testExecuteSucceeds()
    {
        $api = $this->getInstanceForRequestFixture('responses/cancel.xml');
        $cancelAction = new CancelAction();
        $cancelAction->setApi($api);
        $request = new Cancel([]);

        $response = $cancelAction->execute($request);
        Assert::assertEquals('ANNUL', $response->getOperation());
        Assert::assertEquals('OK', $response->getStatus());
    }
}
