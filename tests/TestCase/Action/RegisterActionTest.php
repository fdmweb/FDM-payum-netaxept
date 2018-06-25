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

use FDM\Payum\Netaxept\Action\RegisterAction;
use FDM\Payum\Netaxept\Request\Register;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\Assert;

class RegisterActionTest extends ApiTest
{
    public function testSupportsFailsWithIncorrectRequest()
    {
        $registerAction = new RegisterAction();
        $request = new Capture([]);

        Assert::assertFalse($registerAction->supports($request));
    }

    public function testSupportsFailsWithCorrectRequestButIncorrectModel()
    {
        $registerAction = new RegisterAction();
        $request = new Register(new \stdClass());

        Assert::assertFalse($registerAction->supports($request));
    }

    public function testSupportsSucceedsWithCorrectRequest()
    {
        $registerAction = new RegisterAction();
        $request = new Register([]);

        Assert::assertTrue($registerAction->supports($request));
    }

    /**
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     * @expectedExceptionMessage Action RegisterAction is not supported the request Capture{model: ArrayObject}. Make sure the gateway supports the requests and there is an action which supports this request (The method returns true). There may be a bug, so look for a related issue on the issue tracker.
     */
    public function testExecuteFailsWithIncorrectRequest()
    {
        $registerAction = new RegisterAction();
        $request = new Capture([]);

        $registerAction->execute($request);
    }

    public function testExecuteSucceeds()
    {
        $api = $this->getInstanceForRequestFixture('responses/register.xml');
        $registerAction = new RegisterAction();
        $registerAction->setApi($api);
        $request = new Register([
            "because we're using a fixture file as the response, we don't actually need to send valid data...",
        ]);

        /** @var Register $response */
        $transactionId = $registerAction->execute($request);
        Assert::assertEquals('thisisatransactionidgeneratedbynetaxept', $request->getTransactionId());
        Assert::assertEquals('thisisatransactionidgeneratedbynetaxept', $transactionId);
    }
}
