<?php
namespace Omnipay\Rede\Message;

use Omnipay\Rede\TestCase;

class ResponseTest extends TestCase
{

    private $response = null;

    public function setUp()
    {
        $this->updateResponse();
    }

    private function updateResponse(array $validCodes = ['00'])
    {
        $request = $this->getGateway()->purchase();
        $this->response = new Response($request, [
            'returnCode' => '00',
            'returnMessage' => 'message',
            'reference'  => '01',
            'tid'        => '02'
        ], $validCodes);

    }

    public function testIsSuccessful()
    {
        $this->assertTrue($this->response->isSuccessful());
    }

    public function testIsNotSuccessful()
    {
        $this->updateResponse(['01']);
        $this->assertFalse($this->response->isSuccessful());
    }

    public function testGetReference()
    {
        $this->assertEquals('01', $this->response->getTransactionReference());
    }

    public function testGetTransactionId()
    {
        $this->assertEquals('02', $this->response->getTransactionId());
    }

    public function testGetMessage()
    {
        $this->assertEquals('message', $this->response->getMessage());
    }

}
