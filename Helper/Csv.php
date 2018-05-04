<?php

namespace Corra\Integration\Helper;
class Csv extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * Csv constructor.
     * @param  \Magento\Framework\File\Csv $csvProcessor
     */
    public function __construct(
        \Magento\Framework\File\Csv $csvProcessor
    )
    {
        $this->csvProcessor = $csvProcessor;
    }

    public function importFromCsvFile($filePath,$delimiter = ',')
    {
        if (!isset($filePath)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file'));
        }
        $this->csvProcessor->setDelimiter($delimiter);
        $importProductRawData = $this->csvProcessor->getData($filePath);
        return $importProductRawData;
    }

    public function writeCSV($filePath, $data,$delimiter = ',')
    {
        if (!isset($filePath)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file'));
        }
        $this->csvProcessor->setDelimiter($delimiter);
        $this->csvProcessor->saveData($filePath,$data);
    }


}