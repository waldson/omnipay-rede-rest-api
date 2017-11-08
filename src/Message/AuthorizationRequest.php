<?php
namespace Omnipay\Rede\Message;

use InvalidArgumentException;

class AuthorizationRequest extends AbstractRequest
{

    public function sendData($data)
    {
        $headers = $this->getDefaultHeaders();

        $post = $this->httpClient->post(
            $this->endpoint . '/transactions',
            $headers,
            json_encode($data)
        );

        try {
            $post->send();
        } catch (\Exception $ex) {}

        $response = json_decode(strval($post->getResponse()->getBody()), true);

        return new Response($this, $response);
    }

    public function getData()
    {
        $this->validate('card', 'reference', 'amount');
        $data = parent::getData();

        $card = $this->getCard();

        $data['amount']          = $this->getAmountInteger();
        $data['capture']         = false;
        $data['kind']            = $this->getKind();
        $data['reference']       = $this->getReference();
        $data['installments']    = $this->getInstallments();
        $data['cardHolderName']  = $card->getName();
        $data['cardNumber']      = $card->getNumber();
        $data['expirationYear']  = $card->getExpiryYear();
        $data['expirationMonth'] = $card->getExpiryMonth();
        $data['securityCode']    = $card->getCvv();

        return $data;
    }

    public function setReference($reference)
    {
        return $this->setParameter('reference', $reference);
    }

    public function getKind()
    {
        return $this->getParameter('kind');
    }

    public function setKind($kind)
    {
        $kind = strtolower($kind);
        if ($kind != 'debit' && $kind != 'credit')  {
            throw new InvalidArgumentException("Kind must be 'debit' or 'credit' only.");
        }
        return $this->setParameter('kind', $kind);
    }

    public function getReference()
    {
        return $this->getParameter('reference');
    }

    public function setInstallments($installments)
    {
        if (!filter_var($installments, FILTER_VALIDATE_INT) || $installments < 1 || $installments > 12)  {
            throw new InvalidArgumentException("installments must be an integer from 1 to 12");
        }

        return $this->setParameter('installments', $installments);
    }

    public function getInstallments()
    {
        return $this->getParameter('installments');
    }
}

