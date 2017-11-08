<?php
namespace Omnipay\Rede\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{
    private $validCodes = ['00'];

    public function __construct(
        \Omnipay\Common\Message\RequestInterface $request,
        $data,
        $validCodes = ['00']
    ) {
        parent::__construct($request, $data);
        $this->validCodes = $validCodes;
    }

    public function isSuccessful()
    {
        return in_array($this->getCode(), $this->validCodes);
    }

    public function getCode()
    {
        return $this->get('returnCode');
    }

    public function getTransactionId()
    {
        return $this->get('tid');
    }

    public function getMessage()
    {
        return $this->get('returnMessage');
    }

    public function getTransactionReference()
    {
        return $this->get('reference');
    }

    private function get($key, $default = null)
    {
        return isset($this->data[$key])
               ? $this->data[$key]
               : (isset($this->data['authorization'][$key])
                  ? $this->data['authorization'][$key]
                  : $default);

    }
}
