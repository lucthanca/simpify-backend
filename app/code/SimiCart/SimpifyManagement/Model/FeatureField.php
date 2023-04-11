<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;

class FeatureField extends AbstractModel
{
    /**
     * Init feature field model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\FeatureField::class);
    }
}
