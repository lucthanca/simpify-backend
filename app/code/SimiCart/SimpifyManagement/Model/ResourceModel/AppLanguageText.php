<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use SimiCart\SimpifyManagement\Api\Data\AppLanguageTextInterface;

class AppLanguageText extends AbstractDb
{
    const MAIN_TABLE = 'simicart_simpify_app_language';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'entity_id');
    }

    /**
     * Load app text
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param int $appId
     * @param string $code
     * @return $this
     */
    public function loadAppText(\Magento\Framework\Model\AbstractModel $object, int $appId, string $code)
    {
        $connection = $this->getConnection();
        if ($connection) {
            $select = $this->_getLoadSelect(AppLanguageTextInterface::APP_ID, $appId, $object);
            $select->where(AppLanguageTextInterface::LANGUAGE_CODE . ' = ?', $code);
            $data = $connection->fetchRow($select);

            if ($data) {
                $object->setData($data);
            }
        }
        $this->unserializeFields($object);
        $this->_afterLoad($object);
        $object->afterLoad();
        $object->setOrigData();
        $object->setHasDataChanges(false);
        return $this;
    }
}
