<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel\Theme;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;
use SimiCart\SimpifyManagement\Model\Theme;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = ThemeInterface::ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Theme::class, \SimiCart\SimpifyManagement\Model\ResourceModel\Theme::class);
    }
}
