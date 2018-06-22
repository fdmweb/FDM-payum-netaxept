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
use FDM\Payum\Netaxept\Exception\CancelledException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Authorize;
use Payum\Core\Request\GetHttpRequest;

/**
 * @property Api $api
 */
class AuthorizeAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

    /**
     * {@inheritdoc}
     *
     * @param Authorize $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($rq = new GetHttpRequest());
        $parameters = $rq->query;

        if (empty($parameters['responseCode'])) {
            throw new \LogicException('Missing required responseCode parameter from Netaxept.');
        }

        if (strtolower($parameters['responseCode']) === 'cancel') {
            throw new CancelledException('The user cancelled the transaction in the payment window.');
        }

        if (strtolower($parameters['responseCode']) !== 'ok') {
            throw new \LogicException('Unknown responseCode value from Netaxept.');
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        return $this->api->authorize((array) $model);
    }

    /**
     * {@inheritdoc}
     *
     * @param Authorize $request
     */
    public function supports($request)
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
