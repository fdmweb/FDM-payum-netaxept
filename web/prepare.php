<?php

include __DIR__.'/config.php';

/** @var \Payum\Core\Payum $payum */
$storage = $payum->getStorage(\FDM\Payum\Netaxept\Model\Payment::class);


// Create a payment record, and poke it into storage.
/** @var \FDM\Payum\Netaxept\Model\Payment $payment */
$payment = $storage->create();
$payment->setNumber(rand(10000, 999999999));
$payment->setCurrencyCode('DKK');
$payment->setTotalAmount(rand(99, 2500) * 100);
$payment->setDescription('Test payment');
$storage->update($payment);

// In this example implementation, the authOrCapture query parameter determines which token to generate, so we're just
// using Payum's method for getting it. So this is nothing to actually do with the logic of the token generation or
// anything, it's just getting a parameter the easy way.
/** @var \Payum\Core\Gateway $gateway */
$gateway = $payum->getGateway($gatewayName);
$gateway->execute($rq = new \Payum\Core\Request\GetHttpRequest());
$authOrCapture = $rq->query['authOrCapture'];

// So, once we've redirected the user to the Netaxept payment window (which happens at the bottom of this script) we
// want Netaxept to redirect back to the authorize script or capture script, so we generate that token, here.
/** @var \FDM\Payum\Netaxept\Security\GenericTokenFactory $tokenFactory */
$tokenFactory = $payum->getTokenFactory();
switch ($authOrCapture) {
    case "authorize" : $token = $tokenFactory
        ->createAuthorizeToken($gatewayName, $payment, 'http://localhost/done_but_only_authed.php');
    break;

    case "auth_and_capture" : $token = $tokenFactory
        ->createAuthAndCaptureToken($gatewayName, $payment, 'http://localhost/done_and_captured.php');
    break;

    default: throw new UnexpectedValueException('authOrCapture parameter contains invalid value.');
}

// Next, we need a representation of the payment details in a format that Netaxept will understand, so we make a Convert
// request, which will be actioned by \FDM\Payum\Netaxept\Action\ConvertPaymentAction
$gateway->execute($convert = new \Payum\Core\Request\Convert($payment, 'array', $token));

// Pull the model from the conversion, and ensure it's an ArrayObject. The model is the data that will be sent to
// the Netaxept register endpoint via the Netaxept API.
$model = \Payum\Core\Bridge\Spl\ArrayObject::ensureArrayObject($convert->getResult());

try {
    // Register the transaction with Netaxept. Whether we are generating the transaction ID, or allowing Netaxept to
    // generate it, it is returned by the register request/action. Update the payment model with the transaction ID.
    $gateway->execute($reg = new \FDM\Payum\Netaxept\Request\Register($model));
    $transactionId = $reg->getTransactionId();
    $payment->setTransactionId($transactionId);

    // Now we've injected the transaction ID into the payment, we need to re-convert the model, attach the model to the
    // payment, and store the payment. This is so it contains all required data when it's retrieved by the token in
    // later steps.
    $gateway->execute($convert = new \Payum\Core\Request\Convert($payment, 'array', $token));
    $model = \Payum\Core\Bridge\Spl\ArrayObject::ensureArrayObject($convert->getResult());
    $payment->setDetails($model);
    $storage->update($payment);
} catch (\FDM\Netaxept\Exception\UniqueTransactionIdException $e) {
    // This exception can only be caught if we're providing the transactionId in the $model, as Netaxept will always
    // generate a unique transaction ID.
    die ("The specified transaction ID is not unique: " . $model['transactionId']);
}

// Generate the Netaxept Terminal URL, based on the transactionId, where the user will enter their card details.
$gateway->execute($terminal = new \FDM\Payum\Netaxept\Request\GetTerminalUrl($transactionId));
$uri = $terminal->getTerminalUrl();
if (!$uri instanceof \GuzzleHttp\Psr7\Uri) {
    die("Could not obtain Netaxept terminal URI!");
}

header("Location: ".$uri);