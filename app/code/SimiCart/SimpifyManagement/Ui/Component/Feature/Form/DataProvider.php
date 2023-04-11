<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Feature\Form;

use SimiCart\SimpifyManagement\Model\ResourceModel\Feature\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public function __construct(
        CollectionFactory $featureCollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $featureCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        return [];
    }
}
