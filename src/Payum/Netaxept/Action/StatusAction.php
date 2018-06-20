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

namespace FDM\Payum\Netaxept\Action;

use FDM\Netaxept\Api;
use FDM\Netaxept\Exception\TransactionNotFoundException;
use FDM\Netaxept\Response\Query;
use FDM\Netaxept\Response\QueryInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\BaseGetStatus;
use Payum\Core\Request\GetStatusInterface;

/**
 * @property Api $api
 */
class StatusAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

    /**
     * {@inheritdoc}
     *
     * @param BaseGetStatus $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (empty($model['transactionId'])) {
            return $request->markNew();
        }

        try {
            /** @var Query $queryResponse */
            $queryResponse = $this->api->getTransaction($model['transactionId']);
        } catch (TransactionNotFoundException $e) {
            return $request->markNew();
        }

        switch ($queryResponse->getTransactionStatus()) {
            case QueryInterface::STATUS_PENDING: return $request->markPending();
            case QueryInterface::STATUS_AUTHORIZED: return $request->markAuthorized();
            case QueryInterface::STATUS_CAPTURED: return $request->markCaptured();
            case QueryInterface::STATUS_CANCELLED: return $request->markCanceled();
            case QueryInterface::STATUS_CREDITED: return $request->markRefunded();
        }

        return $request->markFailed();
    }

    /**
     * {@inheritdoc}
     *
     * @param BaseGetStatus $request
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
