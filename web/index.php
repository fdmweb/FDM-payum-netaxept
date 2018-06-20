<style>
    p {
        margin:3em;
        max-width: 50em;
        font-family:sans-serif;
    }
</style>
<p>
Ok, so, click one of the following links to simulate a Netaxept payment process. They both have a common set of actions
up until the user is redirected back from the Netaxept payment window. Those common actions are: The payment object is
created, and registered with Netaxept, with one of three different redirect URLs. The transaction ID returned from the
successful registration with the Netaxept API is then stored with the payment object. The user is then redirected to
the Netaxept terminal window, in which they will enter their card details and complete the payment. Netaxept will then
redirect back to the URL provided in the register call. This is where things differ.
</p>

<p>
<a href="prepare.php?authOrCapture=authorize">Auth only</a>. This will simulate the user buying shippable goods, where
the order total isn't captured from the card until the goods have been shipped; the order total is simply authorized
against the card. When the amount has been authorized, you will be redirected to the "done" page, which will have a link
that simulates capturing the order from the backend of your webshop.
</p>

<p>
<a href="prepare.php?authOrCapture=auth_and_capture">Auth and capture</a>. This will simulate the user buying
"immediate delivery" goods, for example a PDF, or an electronic ticket. The full order amount is authorized and then
captured immediately.
</p>

