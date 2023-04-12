<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldInterface as IFeatureField;

class FeatureField extends AbstractModel implements IFeatureField
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
    public function setName(string $name): self
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getInputType(): string
    {
        return $this->getData(self::INPUT_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setInputType(string $type): self
    {
        return $this->setData(self::INPUT_TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function getFeatureId(): int
    {
        return $this->getData(self::FEATURE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setFeatureId(int $id): self
    {
        return $this->setData(self::FEATURE_ID, $id);
    }
}
