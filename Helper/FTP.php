<?php

namespace Corra\Integration\Helper;

use phpseclib\Net\SFTP;

class FTP extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Corra\Integration\Helper\Config
     */
    protected $config;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * File constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Config $config,
        Logger $logger
    )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param $fileName
     * @param $fileContent
     * @param string $dir
     * @return bool
     */
    public function uploadFileContent($fileName, $fileContent, $dir = "")
    {

        $ftpConfig = $this->config->getFTPConfiguration();

        if (($ftpConfig['host'] != "") && ($ftpConfig['username'] != "") && ($ftpConfig['password'] != "")) {

            $port = $ftpConfig['port'] ? $ftpConfig['port'] : 22;

            try {
                $sftp = new SFTP($ftpConfig['host'], $port);

                $username = $ftpConfig['username'];
                $password = $ftpConfig['password'];

                $sftp->login($username, $password);

                if ($sftp->isConnected()) {

                    if ($dir && !$sftp->file_exists($dir)) {
                        $sftp->mkdir($dir, -1, true);
                    }
                    if (!(substr($dir, -1) == '/')) {
                        $dir .= '/';
                    }

                    $sftp->put($dir . $fileName, $fileContent);
                    return true;
                } else {
                    throw new \Exception("FTP Connection Failed");
                }
            } catch (\Exception $exception) {
                //log exception
                $this->logger->writeLog("Upload Failed. $fileName ERROR - " . $exception->getMessage(), "debug");
                return false;
            }

        } else { //ignore file upload if FTP is not configured
            return true;
        }
    }

    public function downLoadFile($removeFileName, $remoteDir, $localFile)
    {

        $ftpConfig = $this->config->getFTPConfiguration();

        if (($ftpConfig['host'] != "") && ($ftpConfig['username'] != "") && ($ftpConfig['password'] != "")) {

            $port = $ftpConfig['port'] ? $ftpConfig['port'] : 22;

            try {
                $sftp = new SFTP($ftpConfig['host'], $port);

                $username = $ftpConfig['username'];
                $password = $ftpConfig['password'];

                $sftp->login($username, $password);

                if ($sftp->isConnected()) {

                    if ($remoteDir && $sftp->file_exists($remoteDir . $removeFileName)) {
                        $file = $sftp->get($remoteDir . $removeFileName,$localFile);
                    }
                    return $file;
                } else {
                    throw new \Exception("FTP Connection Failed");
                }
            } catch (\Exception $exception) {
                //log exception
                $this->logger->writeLog("Download Failed $removeFileName  ERROR - " . $exception->getMessage(), "debug");
                return false;
            }

        }
        return false;
    }
}
