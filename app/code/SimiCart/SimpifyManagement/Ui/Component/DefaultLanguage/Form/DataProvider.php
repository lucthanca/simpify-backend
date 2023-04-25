<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\DefaultLanguage\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use SimiCart\SimpifyManagement\Api\Data\DefaultLanguageInterface as IDefaultLanguage;
use SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;
    private DataPersistorInterface $dataPersistor;

    /**
     * Data Provider constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Provide data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /**
         * @var IDefaultLanguage $textItem
         */
        foreach ($items as $textItem) {
            $this->loadedData[$textItem->getId()] = $textItem->getData();
        }
        $data = $this->dataPersistor->get('apptext_');
        if (!empty($data)) {
            $this->loadedData[$textItem->getId()] = $data;
            $this->dataPersistor->clear('apptext_');
        }
        dump($this->loadedData);
        return $this->loadedData;
    }
}
