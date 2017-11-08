# Omnipay: Rede
**[Rede Rest API](https://www.userede.com.br/desenvolvedores)'s driver for the Omnipay PHP payment processing library**

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```
composer require waldson/omnipay-rede-rest-api
```


## Supported Methods

The following methods are supported by this package:

* `authorize`
* `capture`
* `purchase` (`authorize` + `catpure` in a single step)
* `refund`
* `fetchTransaction`

## Basic Usage

The following gateway is provided by this package:

* Rede

### Example

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Rede');
$gateway->setMerchantId($yourCV); // Filiação
$gateway->setMerchantKey($yourToken); // Token

$card     = array('number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2030', 'cvv' => '123', 'name' => 'Holder name');
$response = $gateway->purchase(array('amount' => '10.00', 'reference' => '1', 'card' => $card))->send(); //or authorize(...)

if ($response->isSuccessful()) {
    // payment was successful: update database
    $transactionId = $response->getTransactionId();

    //with transactionId you can fetch...
    $transactionInfo = $gateway->fetchTransaction(['transactionId' => $transactionId]); //you can pass 'reference' too

    //refund...
    $response = $gateway->refund(['transactionId' => $transactionId, 'amount' => '10.00']);

    //or capture (don't work with purchase, you can only capture authorized requests)
    $response = $gateway->capture(['transactionId' => $transactionId, 'amount' => '10.00']);

} else {
    // payment failed: display message to customer
    $errorMessage = $response->getMessage();
    $errorCode    = $response->getCode();
    ...
}
```


## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/waldson/omnipay-rede-rest-api/issues),
or better yet, fork the library and submit a pull request.


*PS: **Rede** is a brazillian payment gateway.*