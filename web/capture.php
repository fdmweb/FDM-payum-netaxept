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

// If the payment failed, alert the user.
if ($status->isFailed()) {
    $token = $payum->getTokenFactory()->createAuthorizeToken($gatewayName, $payment, 'http://localhost/payment_failed.php');
    header("Location: " . $token->getAfterUrl());
    exit;
}

// If the user cancelled, then redirect to the cancelled page.
if ($status->isCanceled()) {
    $token = $payum->getTokenFactory()->createAuthorizeToken($gatewayName, $payment, 'http://localhost/user_cancelled.php');
    header("Location: " . $token->getAfterUrl());
    exit;
}

if (!$status->isAuthorized()) {
    throw new LogicException('Only transactions that are registered and authorized can be captured.');
}

$payum->getGateway('netaxept')->execute($convert = new \Payum\Core\Request\Convert($payment, 'array', $token));
$model = \Payum\Core\Bridge\Spl\ArrayObject::ensureArrayObject($convert->getResult());
$gateway->execute(new \Payum\Core\Request\Capture($payment));

$url = $token->getAfterUrl();
header("Location: " . $url);