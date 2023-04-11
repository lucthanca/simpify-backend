<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FeatureFieldOption extends AbstractDb
{
    const MAIN_TABLE = 'simicart_feature_field_options';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'entity_id');
    }
}
