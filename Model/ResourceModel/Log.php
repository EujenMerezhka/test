<?php
/**
 * @category    Corra
 * @package     Corra_FullCircle
 * @author      Corra Team <corra.com>
 */
namespace Corra\Integration\Model\ResourceModel;

class Log extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('corra_log', 'id');
    }
}
