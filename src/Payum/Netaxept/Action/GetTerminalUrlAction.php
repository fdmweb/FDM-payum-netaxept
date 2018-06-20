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
use FDM\Payum\Netaxept\Request\GetTerminalUrl;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Webmozart\Assert\Assert;

/**
 * @property Api $api
 */
class GetTerminalUrlAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
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
     * @param GetTerminalUrl $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        Assert::notEmpty($request->getTransactionId(), 'Transaction ID empty.');

        $request->setTerminalUrl($this->api->getTerminalUri($request->getTransactionId()));
    }

    /**
     * {@inheritdoc}
     *
     * @param GetTerminalUrl $request
     */
    public function supports($request)
    {
        return $request instanceof GetTerminalUrl;
    }
}
