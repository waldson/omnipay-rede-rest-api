<?php
namespace Omnipay\Rede\Message;


use Guzzle\Http\ClientInterface;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Rede\Gateway;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequest extends BaseRequest
{
    protected $authToken = '';
    protected $endpoint  = '';

    public function __construct(
        ClientInterface $httpClient,
        Request $httpRequest,
        $authToken,
        $endPoint
    ) {
        parent::__construct($httpClient, $httpRequest);
        $this->authToken = $authToken;
        $this->endpoint  = $endPoint;
        $this->httpClient->setBaseUrl($this->endpoint);
    }

    public function getData()
    {
        return [];
    }

    public function getDefaultHeaders()
    {
        return [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . $this->authToken,
            'Cache-Control' => 'no-cache'
        ];
    }

    public function getAuthToken()
    {
        return $this->authToken;
    }
}