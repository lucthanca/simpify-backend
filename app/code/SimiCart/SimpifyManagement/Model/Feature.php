<?php

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\FeatureInterface as IFeature;

class Feature extends AbstractModel implements IFeature
{
    /**
     * Init feature model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Feature::class);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): IFeature
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $value): IFeature
    {
        return $this->setData(self::STATUS, $value);
    }
}
