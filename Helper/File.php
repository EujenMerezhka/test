<?php

namespace Corra\Integration\Helper;

use phpseclib\Net\SFTP;

class File extends \Magento\Framework\App\Helper\AbstractHelper
{


    const VAR_DIR = 'var';


    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface|\Magento\Framework\Filesystem\Directory\Write
     */
    protected $varDirectory;

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
        \Magento\Framework\Filesystem $filesystem,
        Config $config,
        Logger $logger
    )
    {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->varDirectory = $filesystem->getDirectoryWrite(self::VAR_DIR);
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param $relativeFileName
     * @param $contents
     * @param string $type
     * @param string $baseDirName
     */
    public function writeFile($relativeFileName, $contents, $baseDirName ,$type = 'order')
    {

        $baseDir = $this->getDirectory($type, $baseDirName,false);

        /** @var \Magento\Framework\Filesystem\File\WriteInterface|\Magento\Framework\Filesystem\File\Write $file */
        $file = $this->varDirectory->openFile($baseDir . $relativeFileName, 'w');

        try {
            $file->lock();
            try {
                $file->write($contents);
            } finally {
                $file->unlock();
            }
        } finally {
            $file->close();
        }

    }

    /**
     * @param string $type
     * @param string $baseDirName
     * @param bool $absPath
     * @return string
     */
    public function getDirectory($type = '', $baseDirName = 'corra',$absPath = true)
    {
        $ds = DIRECTORY_SEPARATOR;
        $path = $baseDirName . $ds . $type;
        $this->varDirectory->create($path);
        if ($absPath)
            return $this->varDirectory->getAbsolutePath($path);
        else
            return $path . $ds;
    }

    /**
     * @param $path
     * @param bool $sort
     * @param array $filter
     * @return array
     * $filter ['ext' => 'extension','prefix'=> 'file prefix filter']
     */
    public function getAllFiles($path,$filter = array(),$sort = true){
        try {
            $flags = \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS;
            $iterator = new \FilesystemIterator($path, $flags);
            $result = [];
            /** @var \FilesystemIterator $file */
            foreach ($iterator as $file) {
                if(!empty($filter)){
                    if($this->filterFile($file,$filter)){
                        $result[$file->getCTime()] = $file->getPathname();
                    }
                } else {
                    $result[$file->getCTime()] = $file->getPathname();
                }
            }
            if($sort){
                ksort($result);
            }
            return $result;
        } catch (\Exception $e) {
            throw new FileSystemException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }

    /**
     * @param $file
     * @param array $filter
     * $filter ['ext' => 'extension','prefix']
     * @return bool
     */
    public function filterFile($file,$filter){
        if(isset($filter['ext'])){
            $extFlag = (strtolower($file->getExtension()) == strtolower($filter['ext']));
            if(!$extFlag){
                return false;
            }
        }
        if(isset($filter['prefix'])){
            $prefixFlag = (0 === strpos($file->getBasename(), $filter['prefix']));
            if(!$prefixFlag){
                return false;
            }
        }

        return true;
    }
}