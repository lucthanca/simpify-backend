<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;

class Theme extends AbstractModel implements ThemeInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Theme::class);
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
    public function setName(?string $name): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getImage(): string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setImage(?string $image): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * @inheritDoc
     */
    public function getPreviewImages(): string
    {
        return $this->getData(self::PREVIEW_IMAGES);
    }

    /**
     * @inheritDoc
     */
    public function setPreviewImages(?string $previewImages): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::PREVIEW_IMAGES, $previewImages);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(?string $createdAt): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(?string $updatedAt): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
