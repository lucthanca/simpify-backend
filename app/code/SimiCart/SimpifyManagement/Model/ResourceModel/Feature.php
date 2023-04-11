<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Feature extends AbstractDb
{
    const MAIN_TABLE = 'simicart_features';

    /**
     * Init feature resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'entity_id');
    }
}
