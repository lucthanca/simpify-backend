<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldOptionInterface;

class FeatureFieldOption extends AbstractDb
{
    const MAIN_TABLE = 'simicart_feature_field_options';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'entity_id');
    }

    /**
     * Load option by value and field id
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param string $value
     * @param int $fieldId
     * @return void
     * @throws InputException
     */
    public function loadByValueAndField(\Magento\Framework\Model\AbstractModel $object, string $value, int $fieldId)
    {
        if (empty($value)) {
            throw new InputException(__("Value should not empty."));
        }
        $connection = $this->getConnection();
        if ($connection) {
            $select = $this->_getLoadSelect(FeatureFieldOptionInterface::VALUE, $value, $object);
            $select->where(FeatureFieldOptionInterface::FIELD_ID, $fieldId);
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
    }
}
