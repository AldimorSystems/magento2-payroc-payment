<?php

namespace AldimorSystems\Payroc\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class PayrocConfigProvider implements ConfigProviderInterface
{
    protected $_scopeConfig;

    const XML_PATH_MESSAGE_TIME = 'payment/aldimorsystems_payroc/payment_action';

    public function __construct
    (
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $config = [];

        $config['payroc']["months"] = $this->getMonths();
        $config['payroc']["years"] = $this->getYears();
        $config['payroc']["capture"] = $this->_scopeConfig->getValue(self::XML_PATH_MESSAGE_TIME, $storeScope);


        return $config;
    }

    public function getMonths(){
        return array(
            "1" => "01 - January",
            "2" => "02 - February",
            "3" => "03 - March",
            "4" => "04 - April",
            "5" => "05 - May",
            "6" => "06 - June",
            "7" => "07 - July",
            "8" => "08 - August",
            "9" => "09 - September",
            "10"=> "10 - October",
            "11"=> "11 - November",
            "12"=> "12 - December"
        );
    }

    public function getYears(){
        $years = array();
        for($i=0; $i<=10; $i++){
            $year = (string)($i+date('Y'));
            $years[$year] = $year;
        }
        return $years;
    }

}
