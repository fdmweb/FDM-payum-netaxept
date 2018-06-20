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

// Generate a capture token, and a cancel token.
/** @var \FDM\Payum\Netaxept\Security\GenericTokenFactory $tokenFactory */
$tokenFactory = $payum->getTokenFactory();
$captureToken = $tokenFactory
    ->createCaptureToken($gatewayName, $payment, 'http://localhost/done_and_captured_backend.php');
$cancelToken = $tokenFactory->createCancelToken($gatewayName, $payment, 'http://localhost/cancelled_backend.php');

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
    Ok, so this is the "Thankyou for placing your order" page, which is shown when the user has bought something that
    has to be shipped to them. The money can only captured after the goods have been shipped. The page may say something
    like:
</p>

<p>
    <em>
        Your order of <b><?= $payment->getDescription() ?></b> has reserved <b><?= $payment->getTotalAmount() / 100 ?>
        <?= $payment->getCurrencyCode() ?></b> on your payment card. The amount will be captured from your payment card
        when the goods have been shipped to you.
    </em>
</p>

<p>
    You can check the status of the payment in the Netaxept admin interface by looking for order ID
    <?= $payment->getNumber() ?> or transaction ID <?= $payment->getTransactionId() ?>
</p>

<p>
    In order to simulate clicking the "capture" button on the order in the backend of your shop,
    <a href="<?= $captureToken->getTargetUrl() ?>">click here.</a> This is of course not a link the user would see, but
    is provided here for demonstration purposes.
</p>

<p>
    In order to simulate clicking the "cancel" button on the order in the backend of your shop,
    <a href="<?= $cancelToken->getTargetUrl() ?>">click here.</a> This is of course not a link the user would see, but
    is provided here for demonstration purposes.
</p>

<p>
    <a href="/">Back to start.</a>
</p>
