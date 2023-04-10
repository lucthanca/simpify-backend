<?php

namespace SimiCart\SimpifyManagement\Model;

use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;
use Magento\Framework\Model\AbstractModel;

class App extends AbstractModel implements IApp
{

    /**
     * @inheritDoc
     */
    public function isSplashFull(): bool
    {
        $v = $this->getData(self::SPLASH_IS_FULL);
        return $v === '1';
    }

    /**
     * @inheritDoc
     */
    public function setSplashIsFull(bool $full): IApp
    {
        return $this->setData(self::SPLASH_IS_FULL, $full);
    }

    /**
     * @inheritDoc
     */
    public function getSplashBgColor(): string
    {
        return $this->getData(self::SPLASH_BG_COLOR);
    }

    /**
     * @inheritDoc
     */
    public function setSplashBgColor(string $color): IApp
    {
        return $this->setData(self::SPLASH_BG_COLOR, $color);
    }

    /**
     * @inheritDoc
     */
    public function getAppLogo(): string
    {
        return $this->getData(self::APP_LOGO);
    }

    /**
     * @inheritDoc
     */
    public function setAppLogo(string $path): self
    {
        return $this->setData(self::APP_LOGO, $path);
    }

    /**
     * @inheritDoc
     */
    public function getSplashImage(): string
    {
        return $this->getData(self::SPLASH_IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setSplashImage(string $path): IApp
    {
        return $this->setData(self::SPLASH_IMAGE, $path);
    }

    /**
     * @inheritDoc
     */
    public function getAppIcon(): string
    {
        return $this->getData(self::APP_ICON);
    }

    /**
     * @inheritDoc
     */
    public function setAppIcon(string $path): IApp
    {
        return $this->setData(self::APP_ICON, $path);
    }

    /**
     * @inheritDoc
     */
    public function getShopId(): int
    {
        return (int) $this->getData(self::SHOP_ID);
    }

    /**
     * @inheritDoc
     */
    public function setShopId(int $shopId): IApp
    {
        return $this->setData(self::SHOP_ID, $shopId);
    }

    /**
     * @inheritDoc
     */
    public function getIndustry(): int
    {
        return (int) $this->getData(self::INDUSTRY);
    }

    /**
     * @inheritDoc
     */
    public function setIndustry(int $value): IApp
    {
        return $this->setData(self::INDUSTRY, $value);
    }
}
