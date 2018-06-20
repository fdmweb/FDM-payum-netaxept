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

namespace FDM\Payum\Netaxept\Request;

use FDM\Netaxept\Response\QueryInterface;
use Payum\Core\Request\BaseGetStatus;

class GetStatus extends BaseGetStatus
{
    /**
     * @var string
     */
    protected $status;

    public function markNew()
    {
        $this->status = QueryInterface::STATUS_UNREGISTERED;
    }

    public function isNew(): bool
    {
        return $this->status === QueryInterface::STATUS_UNREGISTERED;
    }

    public function markAuthorized()
    {
        $this->status = QueryInterface::STATUS_AUTHORIZED;
    }

    public function isAuthorized(): bool
    {
        return $this->status === QueryInterface::STATUS_AUTHORIZED;
    }

    public function markPending()
    {
        $this->status = QueryInterface::STATUS_PENDING;
    }

    public function isPending(): bool
    {
        return $this->status === QueryInterface::STATUS_PENDING;
    }

    public function markCanceled()
    {
        $this->status = QueryInterface::STATUS_CANCELLED;
    }

    public function isCanceled(): bool
    {
        return $this->status === QueryInterface::STATUS_CANCELLED;
    }

    public function markCaptured()
    {
        $this->status = QueryInterface::STATUS_CAPTURED;
    }

    public function isCaptured(): bool
    {
        return $this->status === QueryInterface::STATUS_CAPTURED;
    }

    public function markRefunded()
    {
        $this->status = QueryInterface::STATUS_CREDITED;
    }

    public function isRefunded(): bool
    {
        return $this->status === QueryInterface::STATUS_CREDITED;
    }

    public function markPayedout()
    {
        $this->status = QueryInterface::STATUS_CREDITED;
    }

    public function isPayedout(): bool
    {
        return $this->status === QueryInterface::STATUS_CREDITED;
    }

    public function markFailed()
    {
        $this->status = QueryInterface::STATUS_FAILED;
    }

    public function isFailed(): bool
    {
        return $this->status === QueryInterface::STATUS_FAILED;
    }

    public function markUnknown()
    {
        $this->status = QueryInterface::STATUS_UNREGISTERED;
    }

    public function isUnknown(): bool
    {
        return $this->status === QueryInterface::STATUS_UNREGISTERED;
    }

    /**
     * Netaxept doesn't support this state.
     */
    public function markSuspended()
    {
    }

    /**
     * Netaxept doesn't support this state.
     */
    public function isSuspended(): bool
    {
        return false;
    }

    /**
     * Netaxept doesn't support this state.
     */
    public function markExpired()
    {
    }

    /**
     * Netaxept doesn't support this state.
     */
    public function isExpired(): bool
    {
        return false;
    }
}
