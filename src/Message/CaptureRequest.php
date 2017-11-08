<?php
namespace Omnipay\Rede\Message;

class CaptureRequest extends AbstractRequest
{

    public function sendData($data)
    {
        $headers = $this->getDefaultHeaders();

        $put = $this->httpClient->put(
            $this->endpoint . '/transactions/' . $this->getTransactionId(),
            $headers,
            json_encode($data)
        );

        try {
            $put->send();
        } catch (\Exception $ex) {}

        $response = json_decode(strval($put->getResponse()->getBody()), true);

        return new Response($this, $response);
    }

    public function getData()
    {
        $this->validate('transactionId', 'amount');

        return [
            'amount' => $this->getAmountInteger()
        ];
    }

}

