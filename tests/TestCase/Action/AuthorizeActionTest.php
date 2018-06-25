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

use FDM\Payum\Netaxept\Action\AuthorizeAction;
use Payum\Core\Request\Authorize;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\Assert;

class AuthorizeActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Capture([]);

        Assert::assertFalse($authAction->supports($request));
    }

    public function testSupportsFailsWithCorrectRequestButIncorrectModel()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Authorize(new \stdClass());

        Assert::assertFalse($authAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Authorize([]);

        Assert::assertTrue($authAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action AuthorizeAction is not supported the request Capture{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Capture([]);

        $authAction->execute($request);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Missing required responseCode parameter from Netaxept.
     */
    public function testExecuteFailsWithNoParameters()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Authorize([]);

        $authAction->execute($request);
    }

    /**
     * @expectedException \FDM\Payum\Netaxept\Exception\CancelledException
     * @expectedExceptionMessage The user cancelled the transaction in the payment window.
     */
    public function testExecuteFailsWithResponseCodeCancel()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Authorize([]);

        $_GET['responseCode'] = 'cancel';

        $authAction->execute($request);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Unknown responseCode value from Netaxept.
     */
    public function testExecuteFailsWithOtherResponseCode()
    {
        $api = $this->getInstanceWithNoQueuedRequests();
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Authorize([]);

        $_GET['responseCode'] = 'something that is not cancel or ok';

        $authAction->execute($request);
    }

    public function testExecuteSucceeds()
    {
        $api = $this->getInstanceForRequestFixture('responses/auth.xml');
        $authAction = new AuthorizeAction();
        $authAction->setApi($api);
        $authAction->setGateway($this->gateway);
        $request = new Authorize([]);

        $_GET['responseCode'] = 'OK';

        $response = $authAction->execute($request);
        Assert::assertEquals('AUTH', $response->getOperation());
        Assert::assertEquals('OK', $response->getStatus());
    }
}
