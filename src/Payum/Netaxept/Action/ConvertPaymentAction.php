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
use FDM\Payum\Netaxept\Model\Payment;
use FDM\Payum\Netaxept\Model\TransactionInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

/**
 * @property Api $api
 */
class ConvertPaymentAction implements ActionInterface
{
    /**
     * @var bool
     */
    private $letNetaxeptGenerateTransactionId;

    /**
     * @var ?string
     */
    private $transactionIdTemplate;

    /**
     * ConvertPaymentAction constructor.
     *
     * @param bool $letNetaxeptGenerateTransactionId Whether or not Netaxept should generate a transaction id.
     * @param string|null $transactionIdTemplate
     */
    public function __construct(bool $letNetaxeptGenerateTransactionId = true, string $transactionIdTemplate = null)
    {
        $this->letNetaxeptGenerateTransactionId = $letNetaxeptGenerateTransactionId;
        $this->transactionIdTemplate = $transactionIdTemplate;
    }

    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var Payment $payment */
        $payment = $request->getSource();
        $details = ArrayObject::ensureArrayObject($payment->getDetails());
        if (!$this->letNetaxeptGenerateTransactionId) {
            if ($this->transactionIdTemplate) {
                $details['transactionId'] = sprintf($this->transactionIdTemplate, (int) ($payment->getNumber()));
            } else {
                $details['transactionId'] = $payment->getNumber();
            }
        } else {
            if (!empty($payment->getTransactionId())) {
                $details['transactionId'] = $payment->getTransactionId();
            }
        }
        $details['description'] = $payment->getDescription();
        $details['orderNumber'] = $payment->getNumber();
        $details['currencyCode'] = $payment->getCurrencyCode();
        $details['amount'] = $payment->getTotalAmount();
        $details['language'] = 'da_DK';
        if ($request->getToken()) {
            $details['redirectUrl'] = $request->getToken()->getTargetUrl();
        }

        $request->setResult((array) $details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getSource() instanceof TransactionInterface &&
            $request->getTo() == 'array'
        ;
    }
}
