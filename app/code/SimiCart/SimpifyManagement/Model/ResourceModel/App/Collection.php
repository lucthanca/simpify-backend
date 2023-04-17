<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel\App;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \SimiCart\SimpifyManagement\Model\App::class,
            \SimiCart\SimpifyManagement\Model\ResourceModel\App::class
        );
    }
}
