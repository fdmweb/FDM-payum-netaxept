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
use FDM\Payum\Netaxept\Request\Register;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

/**
 * @property Api $api
 */
class RegisterAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

    /**
     * {@inheritdoc}
     *
     * @param Register $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $response = $this->api->registerTransaction((array) $model);
        $transactionId = $response->getTransactionId();
        $request->setTransactionId($transactionId);

        return $transactionId;
    }

    /**
     * {@inheritdoc}
     *
     * @param Register $request
     */
    public function supports($request)
    {
        return
            $request instanceof Register &&
            $request->getFirstModel() instanceof \ArrayAccess
        ;
    }
}
