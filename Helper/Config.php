<?php

namespace Corra\Integration\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    CONST XML_CONFIG_FTP_USERNAME = 'fc_config/ftp/username';
    CONST XML_CONFIG_FTP_PASSWORD = 'fc_config/ftp/password';
    CONST XML_CONFIG_FTP_HOST = 'fc_config/ftp/host';
    CONST XML_CONFIG_FTP_PORT = 'fc_config/ftp/port';
    CONST XML_CONFIG_FTP_DIRECTORY = 'fc_config/ftp/dir';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }


    public function getFTPConfiguration(){
        $websiteScope = \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE;
        return [
            "username" => $this->scopeConfig->getValue(self::XML_CONFIG_FTP_USERNAME, $websiteScope),

            "password" => $this->scopeConfig->getValue(self::XML_CONFIG_FTP_PASSWORD, $websiteScope),

            "host" => $this->scopeConfig->getValue(self::XML_CONFIG_FTP_HOST, $websiteScope),

            "port" => $this->scopeConfig->getValue(self::XML_CONFIG_FTP_PORT, $websiteScope),
            //
            "directory" => $this->scopeConfig->getValue(self::XML_CONFIG_FTP_DIRECTORY, $websiteScope)
        ];

    }


}