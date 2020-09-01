<?php

namespace AldimorSystems\Payroc\Controller\Payment;

class PaymentController extends \Magento\Framework\App\Action\Action {

    protected $_payment;
    protected $_curl;

    public function __construct
    (
        \AldimorSystems\Payroc\Model\Payment $payment,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\App\Action\Context $context
    )
    {
        $this->_payment = $payment;
        $this->_curl = $curl;
        parent::__construct($context);
    }

    public function execute() {

        $payload = $this->getRequest()->getContent();

        $token = $this->createToken($payload, $this->_payment->getApikey());

        $response = $this->createCharge($token, $payload);

        echo $response;
    }

    private function createToken($payload, $apiKey){
        $digest = hash_hmac('sha256', $payload, $apiKey, true);
        return base64_encode($digest);
    }

    private function createCharge($token, $payload){

        $url = $this->_payment->getUrl();
        $username = $this->_payment->getUsernameEncodeKey();

        $authorization = "Authorization: $username:$token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }
}
