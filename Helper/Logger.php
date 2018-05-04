<?php

namespace Corra\Integration\Helper;

use Braintree\Exception;
use Magento\Framework\App\Filesystem\DirectoryList;

class Logger extends \Magento\Framework\App\Helper\AbstractHelper
{
    CONST EXTN = '.log';
    CONST LOG_TYPE_DEFAULT = 'debug';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $logger;
    protected $directoryList;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Corra\Integration\Model\LogFactory $log
    ){
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $context->getScopeConfig();
        $this->directoryList = $directory_list;
        $this->logFactory = $log;
        parent::__construct($context);
    }

    /**
     * @param string $file
     * @param string $logDir
     * @param bool $useDataDir
     */
    protected function initLogger($file = 'order',$logDir = 'corra' , $useDataDir = true)
    {

        if (!$this->logger) {
            try{
                $DS = DIRECTORY_SEPARATOR;
                $logFile = $file . self::EXTN;

                $logDir = ($logDir)? $logDir. $DS : '';

                if($useDataDir){
                    $dir = $this->directoryList->getPath('log') . $DS . $logDir .date('y-m-d') . $DS;
                    if (!file_exists($dir)) {
                        /** @var \Magento\Framework\Filesystem\Io\File $io * */
                        $io = new \Magento\Framework\Filesystem\Io\File();
                        $io->mkdir($dir);
                    }
                } else {
                    $dir = $this->directoryList->getPath('log') . $DS . $logDir;
                    if (!file_exists($dir)) {
                        /** @var \Magento\Framework\Filesystem\Io\File $io * */
                        $io = new \Magento\Framework\Filesystem\Io\File();
                        $io->mkdir($dir);
                    }
                }

                $logPath = $dir . $logFile;

                if (!empty($logFile)) {
                    $writer = new \Zend\Log\Writer\Stream($logPath);
                    $this->logger = new \Zend\Log\Logger();
                    $this->logger->addWriter($writer);
                }
            } catch (\Exception $e){

            }

        }
    }

    /**
     * @param $message
     * @param string $logType
     * @param string $type
     */
    public function writeLog($message, $logType = self::LOG_TYPE_DEFAULT  ,$type = 'info')
    {
        $this->initLogger($logType);
        if ($type == 'debug') {
            $this->logger->debug($message);
        } else {
            $this->logger->info($message);
        }
    }

    /**
     * @param $message
     * @param null $context
     */
    public function info($message,$context = NULL){
        $this->initLogger('info');
        $this->logger->info($message,$context);
    }

    /**
     * @param $message
     * @param string $logType
     * @return $this
     */
    public function dbLog($message,$logType = self::LOG_TYPE_DEFAULT){
        $logModel = $this->logFactory->create();
        $data = [
            'message' => $message,
            'type' => $logType
        ];
        $logModel->setData($data);
        $logModel->save();
        return $this;
    }


}