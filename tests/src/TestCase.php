<?php
namespace Omnipay\Rede;

use Guzzle\Http\ClientInterface;
use PHPUnit\Framework\TestCase as UnitTestCase;
use Symfony\Component\HttpFoundation\Request;
use Omnipay\Common\CreditCard;

class TestCase extends UnitTestCase
{
    private $gateway;

    public function getHttpClient()
    {
        return $this->createMock(ClientInterface::class);
    }

    public function getHttpRequest()
    {
        return $this->createMock(Request::class);
    }

    protected function populateRequest(Message\AbstractRequest $object)
    {
        $card = new CreditCard();
        $object->setTestMode(true);
        $object->setCard($card);
        $object->getCard()
            ->setName('Waldosn Patrício')
            ->setCvv(123)
            ->setNumber('4485787226954681')
            ->setExpiryYear(date('Y') + 1)
            ->setExpiryMonth(5);
        $object->setAmount(100.00);
        $object->setReference(uniqid());
    }

    protected function populateValidRequest(Message\AbstractRequest $object)
    {
        $card = new CreditCard();
        $object->setTestMode(true);
        $object->setCard($card);
        $object->getCard()
            ->setName('Waldosn Patrício')
            ->setCvv(132)
            ->setNumber('5448280000000007')
            ->setExpiryYear(date('Y') + 1)
            ->setExpiryMonth(1);
        $object->setAmount(100.00);
        $object->setReference(uniqid());
    }

    /**
     * @return Gateway
     */
    protected function getGateway()
    {
        $gateway = \Omnipay\Omnipay::getFactory()->create('Rede');
        $gateway->setTestMode(true);
        $defaultParameters = $gateway->getDefaultParameters();
        $gateway->setMerchantId($defaultParameters['merchantId']);
        $gateway->setMerchantKey($defaultParameters['merchantKey']);

        return $gateway;
    }
}
