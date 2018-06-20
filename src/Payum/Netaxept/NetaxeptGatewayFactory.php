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

namespace FDM\Payum\Netaxept;

use FDM\Netaxept\Api;
use FDM\Payum\Netaxept\Action\AuthorizeAction;
use FDM\Payum\Netaxept\Action\CancelAction;
use FDM\Payum\Netaxept\Action\CaptureAction;
use FDM\Payum\Netaxept\Action\ConvertPaymentAction;
use FDM\Payum\Netaxept\Action\GetTerminalUrlAction;
use FDM\Payum\Netaxept\Action\RefundAction;
use FDM\Payum\Netaxept\Action\RegisterAction;
use FDM\Payum\Netaxept\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\CoreGatewayFactory;
use Payum\Core\GatewayFactoryInterface;

class NetaxeptGatewayFactory implements GatewayFactoryInterface
{
    /**
     * @var GatewayFactoryInterface
     */
    protected $coreGatewayFactory;

    /**
     * @var array
     */
    protected $defaultConfig;

    /**
     * @param array $defaultConfig
     * @param GatewayFactoryInterface $coreGatewayFactory
     */
    public function __construct(array $defaultConfig = [], GatewayFactoryInterface $coreGatewayFactory = null)
    {
        $this->coreGatewayFactory = $coreGatewayFactory ?: new CoreGatewayFactory();
        $this->defaultConfig = $defaultConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config = [])
    {
        return $this->coreGatewayFactory->create($this->createConfig($config));
    }

    /**
     * {@inheritdoc}
     */
    public function createConfig(array $config = [])
    {
        $config = ArrayObject::ensureArrayObject($config);
        $config->defaults($this->defaultConfig);
        $config->defaults($this->coreGatewayFactory->createConfig((array) $config));

        $this->populateConfig($config);

        return (array) $config;
    }

    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $transactionIdTemplate = empty($config['transactionIdTemplate']) ? null : $config['transactionIdTemplate'];
        $letNetaxeptGenerateTransactionId = !empty($config['letNetaxeptGenerateTransactionId']);

        $config->defaults([
            'payum.factory_name' => 'netaxept',
            'payum.factory_title' => 'netaxept',
            'payum.action.status' => new StatusAction(),
            'payum.action.register' => new RegisterAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.terminal_url' => new GetTerminalUrlAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(
                $letNetaxeptGenerateTransactionId,
                $transactionIdTemplate
            ),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'sandbox' => $config['sandbox'],
                'use_authorize' => true,
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];
            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api($config['merchantId'], $config['token'], null, null, null, $config['sandbox']);
            };
        }
    }
}
