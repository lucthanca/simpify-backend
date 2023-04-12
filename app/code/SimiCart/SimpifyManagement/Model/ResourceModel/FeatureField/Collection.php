<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel\FeatureField;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \SimiCart\SimpifyManagement\Model\FeatureField::class,
            \SimiCart\SimpifyManagement\Model\ResourceModel\FeatureField::class
        );
    }
}
