<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

interface AppLayoutInterface
{
    const APP_ID = 'app_id';
    const FONT = 'font';
    const COLORS = 'colors';
    const MENU = 'menu';
    const HOMEPAGE = 'homepage';
    const COLLECTION_PAGE = 'collection_page';
    const PRODUCT_PAGE = 'product_page';
    const LANDING_PAGE = 'landing_page';

    /**
     * Get related app id
     *
     * @return int
     */
    public function getAppId(): int;

    /**
     * Set related app id
     *
     * @param int $id
     * @return $this
     */
    public function setAppId(int $id): self;

    /**
     * Get layout font
     *
     * @return string
     */
    public function getFont(): string;

    /**
     * Set font for layout
     *
     * @param string $fontName
     * @return $this
     */
    public function setLayoutFont(string $fontName): self;

    /**
     * Get raw color data
     *
     * @return string
     */
    public function getColors(): string;

    /**
     * Get json_decoded colors
     *
     * @return array
     */
    public function getDecodedColors(): array;

    /**
     * Encode and set color
     *
     * @param array $colors
     * @return $this
     */
    public function setColors(array $colors): self;

    /**
     * Get raw menu data
     *
     * @return string
     */
    public function getMenu(): string;

    /**
     * Encode n set menu data
     *
     * @param array $data
     * @return $this
     */
    public function setMenu(array $data): self;

    /**
     * Get homepage data
     *
     * @return string
     */
    public function getHomePage(): string;

    /**
     * Set homepage data
     *
     * @param string $data
     * @return $this
     */
    public function setHomepage(string $data): self;

    /**
     * Get collection\product page
     *
     * @return string
     */
    public function getCollectionPage(): string;

    /**
     * Set collection page configuration
     *
     * @param string $data
     * @return $this
     */
    public function setCollectionPage(string $data): self;

    /**
     * Get configured product page
     *
     * @return string
     */
    public function getProductPage(): string;

    /**
     * Set product page
     *
     * @param string $data
     * @return $this
     */
    public function setProductPage(string $data): self;

    /**
     * Get landing page
     *
     * @return string
     */
    public function getLandingPage(): string;

    /**
     * Set landing page data
     *
     * @param string $data
     * @return $this
     */
    public function setLandingPage(string $data): self;

    /**
     * Get related app object
     *
     * @return AppInterface
     */
    public function getApp(): AppInterface;
}
