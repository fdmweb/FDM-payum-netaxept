# Payum Gateway Integration for NETS Netaxept

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Build Status](https://api.travis-ci.com/fdmweb/FDM-payum-netaxept.png?branch=master)](https://travis-ci.org/fdmweb/FDM-payum-netaxept)
[![Latest Stable Version](https://poser.pugx.org/fdm/payum-netaxept/version.png)](https://packagist.org/packages/fdm/payum-netaxept)
[![Code Coverage](https://img.shields.io/codecov/c/github/fdmweb/FDM-payum-netaxept.svg)](https://codecov.io/gh/fdmweb/FDM-payum-netaxept)

This project provides Payum with a Gateway wrapper around the NETS Netaxept API.

## Using the library

To install using composer:

```bash
composer require fdm/payum-netaxept
```

Then, in your code somewhere, get a gateway instance and use it, for example:
```php
$payment = $myPaymentStorage->getPayment(); // Returns a payment object
$gatewayFactory = new \FDM\Payum\Netaxept\NetaxeptGatewayFactory();
$gateway = $gatewayFactory->create([
    'merchantId' => $merchantId,
    'token' => $token,
    'sandbox' => true,
    'letNetaxeptGenerateTransactionId' => true,
    'transactionIdTemplate' => ''
]);
$gateway->execute(new \Payum\Core\Request\Capture($payment));
```

## Contributing

Some tools are provided to ease development. Clone the project and run
`make docker-start` to start a PHP Docker container. Run `make docker-shell` in order
to get a shell inside the container. Run `composer install` to install dependencies.
Run `make test` from inside the container to run tests, and `make codecheck` to make
sure your code follows standards.

## License

Copyright (c) Forenede Danske Motorejere (FDM). All rights reserved.

Licensed under the [MIT](LICENSE) License.  