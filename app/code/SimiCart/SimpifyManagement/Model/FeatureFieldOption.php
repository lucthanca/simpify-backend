<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldOptionInterface as IFeatureFieldOption;

class FeatureFieldOption extends AbstractModel implements IFeatureFieldOption
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\FeatureFieldOption::class);
    }

    /**
     * @inheritDoc
     */
    public function getIsDefault(): int
    {
        return (int) $this->getData(self::IS_DEFAULT);
    }

    /**
     * @inheritDoc
     */
    public function setIsDefault(int $v): IFeatureFieldOption
    {
        return $this->setData(self::IS_DEFAULT, $v);
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): IFeatureFieldOption
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->getData(self::VALUE);
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $v): IFeatureFieldOption
    {
        return $this->setData(self::VALUE, $v);
    }

    /**
     * @inheritDoc
     */
    public function getFieldId(): int
    {
        return (int) $this->getData(self::FIELD_ID);
    }

    /**
     * @inheritDoc
     */
    public function setFieldId(int $id): IFeatureFieldOption
    {
        return $this->setData(self::FIELD_ID, $id);
    }
}
