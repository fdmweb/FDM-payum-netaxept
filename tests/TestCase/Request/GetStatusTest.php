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

use FDM\Netaxept\Response\QueryInterface;
use FDM\Payum\Netaxept\Request\GetStatus;
use PHPUnit\Framework\TestCase;

class GetStatusTest extends TestCase
{
    public function testUnknown()
    {
        $request = new GetStatus([]);
        self::assertEquals(QueryInterface::STATUS_UNREGISTERED, $request->getValue());
        self::assertTrue($request->isUnknown());
    }

    public function testPending()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markPending();
        self::assertEquals(QueryInterface::STATUS_PENDING, $request->getValue());
        self::assertTrue($request->isPending());
    }

    public function testCanceled()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markCanceled();
        self::assertEquals(QueryInterface::STATUS_CANCELLED, $request->getValue());
        self::assertTrue($request->isCanceled());
    }

    public function testNew()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markNew();
        self::assertEquals(QueryInterface::STATUS_UNREGISTERED, $request->getValue());
        self::assertTrue($request->isNew());
    }

    public function testFailed()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markFailed();
        self::assertEquals(QueryInterface::STATUS_FAILED, $request->getValue());
        self::assertTrue($request->isFailed());
    }

    public function testRefunded()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markRefunded();
        self::assertEquals(QueryInterface::STATUS_CREDITED, $request->getValue());
        self::assertTrue($request->isRefunded());
    }

    public function testPayedout()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markPayedout();
        self::assertEquals(QueryInterface::STATUS_CREDITED, $request->getValue());
        self::assertTrue($request->isPayedout());
    }

    public function testCaptured()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markCaptured();
        self::assertEquals(QueryInterface::STATUS_CAPTURED, $request->getValue());
        self::assertTrue($request->isCaptured());
    }

    public function testAuthorized()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markAuthorized();
        self::assertEquals(QueryInterface::STATUS_AUTHORIZED, $request->getValue());
        self::assertTrue($request->isAuthorized());
    }

    public function testExpired()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markExpired();
        self::assertFalse($request->isExpired()); // Expired is not supported, so will always return false.
    }

    public function testSuspended()
    {
        $request = new GetStatus([]);
        self::assertTrue($request->isUnknown());
        $request->markSuspended();
        self::assertFalse($request->isSuspended()); // Suspend is not supported, so will always return false.
    }
}
