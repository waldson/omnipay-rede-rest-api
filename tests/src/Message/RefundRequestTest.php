<?php
namespace Omnipay\Rede\Message;

use Omnipay\Rede\TestCase;

class RefundRequestTest extends TestCase
{

    public function testSuccessfulRefund()
    {
        $gateway  = $this->getGateway();
        $object   = $gateway->purchase();
        $this->populateValidRequest($object);

        $response = $object->send();
        $this->assertTrue($response->isSuccessful());
        $transactionId = $response->getTransactionId();

        $captureResponse = $gateway->refund([
            'transactionId' => $transactionId,
            'amount'        => $object->getAmount()
        ])->send();

        $this->assertTrue($captureResponse->isSuccessful());
    }

    public function testFailedCapture()
    {
        $gateway  = $this->getGateway();
        $response = $gateway->refund([
            'transactionId' => uniqid(),
            'amount'        => 100.00
        ])->send();

        $this->assertFalse($response->isSuccessful());
    }

}
