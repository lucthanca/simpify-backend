<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use SimiCart\SimpifyManagement\Model\ResourceModel\Theme\CollectionFactory;

class FormThemeDataProvider extends AbstractDataProvider
{
    public function __construct(
        CollectionFactory $collectionFactory,
        $name, $primaryFieldName, $requestFieldName, array $meta = [], array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}
