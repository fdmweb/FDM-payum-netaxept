<?php

require '../vendor/autoload.php';
require 'vars.php';

use FDM\Payum\Netaxept\Model\Payment;

$gatewayName = 'netaxept';

/** @var Payum $payum */
$payum = (new \FDM\Payum\Netaxept\PayumBuilder())
    ->addDefaultStorages()
    ->addStorage(Payment::class, new \Payum\Core\Storage\FilesystemStorage(sys_get_temp_dir(), Payment::class, 'number'))
    ->addGatewayFactory('netaxept', new \FDM\Payum\Netaxept\NetaxeptGatewayFactory([
        'merchantId' => $merchantId,
        'token' => $token,
        'sandbox' => true,
        'letNetaxeptGenerateTransactionId' => $letNetaxeptGenerateTransactionId,
        'transactionIdTemplate' => $transactionIdTemplate
    ]))
    ->addGateway('netaxept', [
        'factory' => 'netaxept',
    ])
    ->setGenericTokenFactoryPaths([
        'authorize' => 'http://localhost/authorize.php',
        'cancel' => 'http://localhost/cancel.php',
        'auth_and_capture' => 'http://localhost/authorize_and_capture.php',
        'capture' => 'http://localhost/capture.php',
        'refund' => 'http://localhost/refund.php',
    ])
    ->getPayum()
;



