<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\AppLanguageTextInterface;

class AppLanguageText extends AbstractModel implements AppLanguageTextInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\AppLanguageText::class);
    }

    /**
     * @inheritDoc
     */
    public function getAppId(): int
    {
        return (int)$this->getData(self::APP_ID);
    }

    /**
     * @inheritDoc
     */
    public function setAppId(int $appId): AppLanguageTextInterface
    {
        return $this->setData(self::APP_ID, $appId);
    }

    /**
     * @inheritDoc
     */
    public function getLanguageCode(): string
    {
        return $this->getData(self::LANGUAGE_CODE);
    }

    /**
     * @inheritDoc
     */
    public function setLanguageCode(string $languageCode): AppLanguageTextInterface
    {
        return $this->setData(self::LANGUAGE_CODE, $languageCode);
    }

    /**
     * @inheritDoc
     */
    public function getTextData(): string
    {
        return $this->getData(self::TEXT_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setTextData(string $textData): AppLanguageTextInterface
    {
        return $this->setData(self::TEXT_DATA, $textData);
    }
}
