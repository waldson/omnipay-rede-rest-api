<?php
namespace Omnipay\Rede;

class GatewayTest extends \PHPUnit\Framework\TestCase
{
    private $gateway;

    protected function setUp()
    {
        $this->gateway = new Gateway();
    }

    public function testGatewayInCorrectNamespace()
    {
        $this->assertEquals('Omnipay\\Rede\\Gateway', get_class($this->gateway));
    }

    public function testIsReturningCorrectlyFromFactory()
    {
        $gateway = \Omnipay\Omnipay::getFactory()->create('Rede');
        $this->assertEquals('Omnipay\\Rede\\Gateway', get_class($gateway));
    }

    public function testIsInstanceOfAbsctractGateway()
    {
        $gateway = \Omnipay\Omnipay::getFactory()->create('Rede');
        $this->assertTrue(($gateway instanceof \Omnipay\Common\AbstractGateway));
    }

    public function testHasCorrectName()
    {
        $this->assertEquals('Rede Gateway', $this->gateway->getName());
    }

    public function testHasCorrectProductionEndpoint()
    {
        $this->assertEquals(
            'https://api.userede.com.br/erede/v1',
            Gateway::LIVE_ENDPOINT
        );

    }

    public function testHasCorrectTestEndpoint()
    {
        $this->assertEquals(
            'https://api-hom.userede.com.br/erede/v1',
            Gateway::TEST_ENDPOINT
        );
    }

    public function testReturningCorrectEndpoint()
    {
       $this->gateway->setTestMode(true);
       $this->assertEquals($this->gateway->getEndpoint(), Gateway::TEST_ENDPOINT);
       $this->gateway->setTestMode(false);
       $this->assertEquals($this->gateway->getEndpoint(), Gateway::LIVE_ENDPOINT);
    }

    public function testHasDefaultParameters()
    {
        $this->assertEquals(
            [
                'merchantId' => null,
                'testMode'   => false,
                'merchantKey' => null,
                'installments' => 1,
                'capture'      => true,
                'kind'         => 'credit',
                'origin'       => 1
            ],
            $this->gateway->getDefaultParameters()
        );
    }

    public function testHasDefaultParametersOnTestMode()
    {
        $this->gateway->setTestMode(true);
        $this->assertEquals(
            [
                'merchantId'  => '50079557',
                'testMode'    => true,
                'merchantKey' => '4913bb24a0284954be72c4258e229b86',
                'installments' => 1,
                'capture'      => true,
                'kind'         => 'credit',
                'origin'       => 1
            ],
            $this->gateway->getDefaultParameters()
        );
    }

    public function testIsEncodingCredentialsCorrectly()
    {
        $merchantId  = '50079557';
        $merchantKey = '4913bb24a0284954be72c4258e229b86';

        $this->assertEquals(
            'NTAwNzk1NTc6NDkxM2JiMjRhMDI4NDk1NGJlNzJjNDI1OGUyMjliODY=',
            $this->gateway->encodeCredentials($merchantId, $merchantKey)
        );

        for ($i = 0; $i <= 100; ++$i) {
            $id  = uniqid('rede.');
            $key = sha1(uniqid('rede.'));

            $expected = base64_encode($id . ':' . $key);

            $this->assertEquals(
                $expected,
                $this->gateway->encodeCredentials($id, $key)
            );
        }
    }

    public function testGetSetMerchantId()
    {
        $merchantId = uniqid();
        $this->gateway->setMerchantId($merchantId);
        $this->assertEquals($merchantId, $this->gateway->getMerchantId());
    }

    public function testGetSetMerchantKey()
    {
        $merchantId = uniqid();
        $this->gateway->setMerchantKey($merchantId);
        $this->assertEquals($merchantId, $this->gateway->getMerchantKey());
    }

    public function __testExecuteTransaction()
    {
        /* @var $gateway Gateway */
        $gateway = \Omnipay\Omnipay::getFactory()->create('Rede');
        $gateway->setTestMode(true);
        $defaultParameters = $gateway->getDefaultParameters();


        $gateway->setMerchantId($defaultParameters['merchantId']);
        $gateway->setMerchantKey($defaultParameters['merchantKey']);

        $card = [
            'number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2022', 'cvv' => '123',
            'name' => 'Test'
        ];
        $response = $gateway->authorize([
            'card' => $card,
            'amount' => 100.20,
            'installments' => 1,
            'reference' => 11
        ])->send();

    }

}
