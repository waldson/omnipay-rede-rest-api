<?php
namespace Omnipay\Rede\Message;

use Omnipay\Rede\TestCase;

class FetchTransactionRequestTest extends TestCase
{

    public function testSuccessfulFetchByReference()
    {
        $gateway  = $this->getGateway();
        $object   = $gateway->purchase();
        $this->populateValidRequest($object);

        $response = $object->send();
        $this->assertTrue($response->isSuccessful());
        $transactionId = $response->getTransactionId();

        $transaction = $gateway->fetchTransaction([
            'reference' => $object->getReference()
        ])->send();

        $this->assertTrue($transaction->isSuccessful());
        $this->assertEquals($transactionId, $transaction->getTransactionId());
    }

    public function testFailedFetchByReference()
    {
        $gateway  = $this->getGateway();
        $response = $gateway->fetchTransaction([
            'reference' => uniqid(),
        ])->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function testSuccessfulFetchByTransactionId()
    {
        $gateway  = $this->getGateway();
        $object   = $gateway->purchase();
        $this->populateValidRequest($object);

        $response = $object->send();
        $this->assertTrue($response->isSuccessful());
        $transactionId = $response->getTransactionId();

        $transaction = $gateway->fetchTransaction([
            'transactionId' => $transactionId
        ])->send();

        $this->assertTrue($transaction->isSuccessful());
        $this->assertEquals($transactionId, $transaction->getTransactionId());
    }

    public function testFailedFetchByTransactionId()
    {
        $gateway  = $this->getGateway();
        $response = $gateway->fetchTransaction([
            'transactionId' => uniqid(),
        ])->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function testGetReference()
    {
        $reference = uniqid();
        $gateway  = $this->getGateway();
        $response = $gateway->fetchTransaction([
            'reference' => $reference,
        ]);

        $this->assertEquals($reference, $response->getReference());
    }

    public function testGetData()
    {
        $reference = uniqid();
        $gateway  = $this->getGateway();
        $response = $gateway->fetchTransaction([
            'reference' => $reference,
        ]);

        $data = $response->getData();
        $this->assertEquals($reference, $data['reference']);


        $response = $gateway->fetchTransaction([
            'transactionId' => $reference,
        ]);

        $data = $response->getData();

        $this->assertEquals($reference, $data['transactionId']);
    }

    public function testMustThrowWhenRequestIsInvalid()
    {
        $this->expectException(\Exception::class);
        $gateway  = $this->getGateway();
        $response = $gateway->fetchTransaction([])->send();
    }

}
