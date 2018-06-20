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

// Invalidate the token.
$payum->getHttpRequestVerifier()->invalidate($token);
?>
<style>
    p {
        margin:3em;
        max-width: 50em;
        font-family:sans-serif;
    }
</style>
<p>
    Well, it appears the payment failed, probably because the card was declined. You will probably see this if you use
    one of the test cards that fails auth or capture.
</p>

<p>
    You can check the status of the payment in the Netaxept admin interface by looking for order ID
    <?= $payment->getNumber() ?> or transaction ID <?= $payment->getTransactionId() ?>
</p>

<p>
    <a href="/">Back to start.</a>
</p>
