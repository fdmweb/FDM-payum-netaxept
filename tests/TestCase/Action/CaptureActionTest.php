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

use FDM\Payum\Netaxept\Action\CaptureAction;
use Payum\Core\Request\Cancel;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\Assert;

class CaptureActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $captureAction = new CaptureAction();
        $captureAction->setApi($api);
        $request = new Cancel([]);

        Assert::assertFalse($captureAction->supports($request));
    }

    public function testSupportsFailsWithCorrectRequestButIncorrectModel()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $captureAction = new CaptureAction();
        $captureAction->setApi($api);
        $request = new Capture(new \stdClass());

        Assert::assertFalse($captureAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $captureAction = new CaptureAction();
        $captureAction->setApi($api);
        $request = new Capture([]);

        Assert::assertTrue($captureAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action CaptureAction is not supported the request Cancel{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $captureAction = new CaptureAction();
        $captureAction->setApi($api);
        $request = new Cancel([]);

        $captureAction->execute($request);
    }

    public function testExecuteSucceeds()
    {
        $api = $this->getInstanceForRequestFixture('responses/capture.xml');
        $captureAction = new CaptureAction();
        $captureAction->setApi($api);
        $request = new Capture([]);

        $response = $captureAction->execute($request);
        Assert::assertEquals('CAPTURE', $response->getOperation());
        Assert::assertEquals('OK', $response->getStatus());
    }
}
