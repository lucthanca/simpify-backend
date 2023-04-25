<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\DefaultLanguageInterface as IDefaultLanguage;

class DefaultLanguage extends AbstractModel implements IDefaultLanguage
{
    protected function _construct()
    {
        $this->_init(ResourceModel\DefaultLanguage::class);
    }

    /**
     * @inheritDoc
     */
    public function getText(): ?string
    {
        return $this->getData(self::TEXT);
    }

    /**
     * @inheritDoc
     */
    public function setText($text): IDefaultLanguage
    {
        return $this->setData(self::TEXT, $text);
    }
}
