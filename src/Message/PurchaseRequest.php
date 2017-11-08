<?php
namespace Omnipay\Rede\Message;

class PurchaseRequest extends AuthorizationRequest
{

    public function getData()
    {
        $data            = parent::getData();
        $data['capture'] = true;
        return $data;
    }
}
