<?php

include __DIR__.'/config.php';

// Grab the token, the storage, and the payment record from storage using the ID stored in the token.
/** @var \Payum\Core\Payum $payum */
$token = $payum->getHttpRequestVerifier()->verify($_REQUEST);
$storage = $payum->getStorage(\FDM\Payum\Netaxept\Model\Payment::class);
/** @var \FDM\Payum\Netaxept\Model\Payment $payment */
$payment = $storage->find($token->getDetails()->getId());

// Grab the status of the payment.
$gatewayName = $token->getGatewayName();
/** @var \Payum\Core\Gateway $gateway */
$gateway = $payum->getGateway($gatewayName);
$gateway->execute($status = new \FDM\Payum\Netaxept\Request\GetStatus($payment));

// It's possible the payment failed, because the card was declined.
if ($status->isFailed()) {
    $token = $payum->getTokenFactory()
        ->createAuthorizeToken($gatewayName, $payment, 'http://localhost/payment_failed.php');
    header("Location: " . $token->getAfterUrl());
    exit;
}

// It's also possible the user cancelled the transaction by clicking "cancel" in the payment window.
if ($status->isCanceled()) {
    $token = $payum->getTokenFactory()
        ->createAuthorizeToken($gatewayName, $payment, 'http://localhost/user_cancelled.php');
    header("Location: " . $token->getAfterUrl());
    exit;
}

// There's a slim chance the transaction is in some other state
if (!$status->isPending()) {
    throw new LogicException('Only transactions that are registered, and are therefore in the "pending" state, can' .
        ' be authorized.');
}

$payum->getGateway('netaxept')->execute($convert = new \Payum\Core\Request\Convert($payment, 'array', $token));
$model = \Payum\Core\Bridge\Spl\ArrayObject::ensureArrayObject($convert->getResult());
$gateway->execute(new \Payum\Core\Request\Authorize($model));

$url = $token->getAfterUrl();
header("Location: " . $url);