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

// Generate a refund token.
/** @var \FDM\Payum\Netaxept\Security\GenericTokenFactory $tokenFactory */
$tokenFactory = $payum->getTokenFactory();
$refundToken = $tokenFactory->createRefundToken($gatewayName, $payment, 'http://localhost/refunded_backend.php');

// Invalidate the original token.
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
    So, in theory, this page would never actually be seen, it would probably be hidden away as an AJAX request target
    URL, that would just delete the token and change the state of the order in the webshop to "complete". But the fact
    you're seeing this page means you must have just clicked the simulated "capture" button, and the payment was
    successfully captured from the card by Netaxept.
</p>

<p>
    You can check the status of the payment in the Netaxept admin interface by looking for order ID
    <?= $payment->getNumber() ?> or transaction ID <?= $payment->getTransactionId() ?>
</p>

<p>
    In order to simulate clicking the "refund" button on the order in the backend of your shop,
    <a href="<?= $refundToken->getTargetUrl() ?>">click here.</a> This is of course not a link the user would see, but
    is provided here for demonstration purposes.
</p>

<p>
    <a href="/">Back to start.</a>
</p>
