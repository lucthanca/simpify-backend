<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Feature\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use SimiCart\SimpifyManagement\Api\Data\FeatureInterface as IFeature;
use SimiCart\SimpifyManagement\Model\ResourceModel\Feature\CollectionFactory;

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
     * @param CollectionFactory $featureCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $featureCollectionFactory,
        DataPersistorInterface $dataPersistor,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $featureCollectionFactory->create();
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
         * @var IFeature $feature
         */
        foreach ($items as $feature) {
            $this->loadedData[$feature->getId()]['feature'] = $feature->getData();
        }
        $data = $this->dataPersistor->get('feature_');
        if (!empty($data)) {
            $this->loadedData[$feature->getId()] = $data;
            $this->dataPersistor->clear('feature_');
        }
        return $this->loadedData;
    }
}
