<?php
/**
 * @category    Corra
 * @package     Corra_FullCircle
 * @author      Corra Team <corra.com>
 */

    namespace Corra\Integration\Model;

class Log extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Corra\Integration\Model\ResourceModel\Log');
    }
}
