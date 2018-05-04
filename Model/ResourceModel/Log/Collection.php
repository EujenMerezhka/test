<?php
/**
 * @category    Corra
 * @package     Corra_FullCircle
 * @author      Corra Team <corra.com>
 */

namespace Corra\Integration\Model\ResourceModel\Log;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Corra\Integration\Model\Log', 'Corra\Integration\Model\ResourceModel\Log');
    }
}
