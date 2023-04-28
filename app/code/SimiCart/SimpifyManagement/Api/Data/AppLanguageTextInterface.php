<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

interface AppLanguageTextInterface
{
    const APP_ID = 'app_id';
    const LANGUAGE_CODE = 'code';
    const TEXT_DATA = 'text_data';

    /**
     * Get app id
     *
     * @return int
     */
    public function getAppId(): int;

    /**
     * Set app id
     *
     * @param int $appId
     * @return $this
     */
    public function setAppId(int $appId): self;

    /**
     * Get language code
     *
     * @return string
     */
    public function getLanguageCode(): string;

    /**
     * Set language code
     *
     * @param string $languageCode
     * @return $this
     */
    public function setLanguageCode(string $languageCode): self;

    /**
     * Get text data
     *
     * @return string
     */
    public function getTextData(): string;

    /**
     * Set text data
     *
     * @param string $textData
     * @return $this
     */
    public function setTextData(string $textData): self;
}
