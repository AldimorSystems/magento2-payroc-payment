<?php

namespace AldimorSystems\Payroc\Model;

use Magento\Directory\Helper\Data as DirectoryHelper;

class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{

    CONST LIVE_URL = "https://api.itransact.com/transactions";
    CONST SANDBOX_URL = "https://sandbox-api.itransact.com/transactions";

    protected $live_url = SELF::LIVE_URL;
    protected $sandbox_url = SELF::SANDBOX_URL;

    protected $_encryptor;

    protected $_isSandbox;
    protected $_url;
    protected $_usernameKey;
    protected $_usernameEncodeKey;
    protected $_apiKey;


    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'aldimorsystems_payroc';

    public function __construct(
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        array $data = [],
        DirectoryHelper $directory = null
    ){
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            null,
            null,
            $data,
            null
        );

        $this->_encryptor = $encryptor;

        $this->_isSandbox = $this->getConfigData('is_sandbox');
        $this->_url = $this->_isSandbox ? $this->sandbox_url : $this->live_url;
        $this->_usernameKey = $this->_isSandbox ? $this->getConfigData('sandbox_username_key') : $this->getConfigData('live_username_key');
        $this->_usernameEncodeKey = $this->_isSandbox ? $this->getConfigData('sandbox_username_encode_key') : $this->getConfigData('live_username_encode_key');
        $this->_apiKey = $this->_isSandbox ? $this->_encryptor->decrypt($this->getConfigData('sandbox_api_key')) : $this->_encryptor->decrypt($this->getConfigData('live_api_key'));
    }

    private function isSandbox(){
        return $this->_isSandbox;
    }

    public function getUrl(){
        return $this->_url;
    }

    public function getUsernameKey(){
        return $this->_usernameKey;
    }

    public function getUsernameEncodeKey(){
        return $this->_usernameEncodeKey;
    }

    public function getApikey(){
        return $this->_apiKey;
    }

}
