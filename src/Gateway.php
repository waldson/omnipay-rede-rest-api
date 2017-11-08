<?php
namespace Omnipay\Rede;

class Gateway extends \Omnipay\Common\AbstractGateway
{
    const LIVE_ENDPOINT = 'https://api.userede.com.br/erede/v1';
    const TEST_ENDPOINT = 'https://api-hom.userede.com.br/erede/v1';

    const KEY_MERCHANT_ID  = 'merchantId';
    const KEY_MERCHANT_KEY = 'merchantKey';

    public function getName()
    {
        return 'Rede Gateway';
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? self::TEST_ENDPOINT : self::LIVE_ENDPOINT;
    }

    public function getDefaultParameters()
    {
        if ($this->getTestMode()) {
            return [
                self::KEY_MERCHANT_ID  => '50079557',
                'testMode'    => true,
                self::KEY_MERCHANT_KEY => '4913bb24a0284954be72c4258e229b86',
                'installments' => 1,
                'capture'      => true,
                'kind'         => 'credit',
                'origin'       => 1
            ];
        }
        return [
            self::KEY_MERCHANT_ID => null,
            'testMode'   => false,
            self::KEY_MERCHANT_KEY => null,
            'installments' => 1,
            'capture'      => true,
            'kind'         => 'credit',
            'origin'       => 1
        ];
    }

    public function encodeCredentials($merchantId, $merchantKey)
    {
        return base64_encode($merchantId . ':' . $merchantKey);
    }

    public function setMerchantId($merchantId)
    {
        $this->setParameter(self::KEY_MERCHANT_ID, $merchantId);
    }

    public function getMerchantId()
    {
        return $this->getParameter(self::KEY_MERCHANT_ID);
    }

    public function setMerchantKey($merchantKey)
    {
        $this->setParameter(self::KEY_MERCHANT_KEY, $merchantKey);
    }

    public function getMerchantKey()
    {
        return $this->getParameter(self::KEY_MERCHANT_KEY);
    }

     /**
     * Create an authorize request.
     *
     * @param array $parameters
     * @return \Omnipay\Rede\Message\AuthorizeRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(
            Message\AuthorizationRequest::class,
            $parameters
        );
    }

     /**
     * Create an purchase request.
     *
     * @param array $parameters
     * @return \Omnipay\Rede\Message\AuthorizeRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(
            Message\PurchaseRequest::class,
            $parameters
        );
    }

    /**
     * Create a capture request.
     *
     * @param array $parameters
     * @return \Omnipay\Rede\Message\CaptureRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(
            \Omnipay\Rede\Message\CaptureRequest::class,
            $parameters
        );
    }

    /**
     * Create a refund request.
     *
     * @param array $parameters
     * @return \Omnipay\Rede\Message\RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(
            \Omnipay\Rede\Message\RefundRequest::class,
            $parameters
        );
    }

    /**
     * Create a fetch transaction request.
     *
     * @param array $parameters
     * @return \Omnipay\Rede\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(
            \Omnipay\Rede\Message\FetchTransactionRequest::class,
            $parameters
        );
    }



    protected function createRequest($class, array $parameters)
    {
        $gatewayParameters = $this->getParameters();
        $merchantId        = isset($gatewayParameters[self::KEY_MERCHANT_ID])
                           ? $gatewayParameters[self::KEY_MERCHANT_ID]
                           : '';
        $merchantKey        = isset($gatewayParameters[self::KEY_MERCHANT_KEY])
                            ? $gatewayParameters[self::KEY_MERCHANT_KEY]
                            : '';

        $obj = new $class(
            $this->httpClient,
            $this->httpRequest,
            $this->encodeCredentials($merchantId, $merchantKey),
            $this->getEndpoint()
        );

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

}
