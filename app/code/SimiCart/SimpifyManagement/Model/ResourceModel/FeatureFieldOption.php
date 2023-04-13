<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\ResourceModel;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldOptionInterface as IFeatureFieldOption;

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
            $select = $this->_getLoadSelect(IFeatureFieldOption::VALUE, $value, $object);
            $select->where(IFeatureFieldOption::FIELD_ID, $fieldId);
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

    /**
     * Quick delete
     *
     * @param int $fieldId
     * @param array $ids
     * @param string $mode - nin and in
     * @return void
     * @throws LocalizedException
     */
    public function quickDeleteByFieldAndIds(int $fieldId, array $ids, string $mode = 'nin')
    {
        $connection = $this->transactionManager->start($this->getConnection());
        try {
            $condition = $mode === 'nin' ? 'NOT IN' : 'IN';
            $conditionStr = sprintf(
                '%s=%s AND %s %s (%s)',
                IFeatureFieldOption::FIELD_ID,
                $fieldId,
                IFeatureFieldOption::ID,
                $condition,
                implode(",", $ids)
            );
            $connection->delete(
                $this->getMainTable(),
                $conditionStr
            );
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }
    }
}
