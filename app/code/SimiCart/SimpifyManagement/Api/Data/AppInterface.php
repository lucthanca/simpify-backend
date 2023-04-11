<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Api\Data;

interface AppInterface
{
    const SHOP_ID = 'shop_id';
    const INDUSTRY = 'industry';
    const APP_LOGO = 'app_logo';
    const APP_ICON = 'app_icon';

    const SPLASH_IMAGE = 'splash_image';
    const SPLASH_BG_COLOR = 'splash_bg_color';
    const SPLASH_IS_FULL = 'splash_is_full';

    /**
     * Get splash is full
     *
     * @return bool
     */
    public function isSplashFull(): bool;

    /**
     * Set splash is full
     *
     * @param bool $full
     * @return $this
     */
    public function setSplashIsFull(bool $full): self;

    /**
     * Get splash background color
     *
     * @return string
     */
    public function getSplashBgColor(): string;

    /**
     * Set splash bg color
     *
     * @param string $color
     * @return $this
     */
    public function setSplashBgColor(string $color): self;

    /**
     * Get app logo path
     *
     * @return string
     */
    public function getAppLogo(): string;

    /**
     * Set app logo path
     *
     * @param string $path
     * @return self
     */
    public function setAppLogo(string $path): self;

    /**
     * Get app splash image path
     *
     * @return string
     */
    public function getSplashImage(): string;

    /**
     * Set app splash image path
     *
     * @param string $path
     * @return self
     */
    public function setSplashImage(string $path): self;

    /**
     * Get app icon path
     *
     * @return string
     */
    public function getAppIcon(): string;

    /**
     * Set app icon path
     *
     * @param string $path
     * @return self
     */
    public function setAppIcon(string $path): self;

    /**
     * Get shop\user id
     *
     * @return int
     */
    public function getShopId(): int;

    /**
     * Set shop/user id
     *
     * @param int $shopId
     * @return self
     */
    public function setShopId(int $shopId): self;

    /**
     * Get shop industry
     *
     * @return Int
     */
    public function getIndustry(): int;

    /**
     * Set app industry
     *
     * @param int $value
     * @return $this
     */
    public function setIndustry(int $value): self;

    /**
     * Get app layout
     *
     * @return AppLayoutInterface
     */
    public function getAppLayout(): AppLayoutInterface;
}
