<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\FeatureField\Listing;

use Magento\Framework\App\RequestInterface as IRequest;
use SimiCart\SimpifyManagement\Model\ResourceModel\FeatureField\CollectionFactory;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldInterface as IFeatureField;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected IRequest $request;

    /**
     * Feature Field data provider constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param IRequest          $request,
     * @param string            $name
     * @param string            $primaryFieldName
     * @param string            $requestFieldName
     * @param array             $meta
     * @param array             $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        IRequest          $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array             $meta = [],
        array             $data = []
    ) {
        $this->collection =$collectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Prepare data for listing
     *
     * @return array
     */
    public function getData(): array
    {
        $collection = $this->getCollection();
        if ($parentId = $this->request->getParam('parent_id')) {
            $collection->addFieldToFilter([IFeatureField::FEATURE_ID], [["eq" => (int) $parentId],]);
        }
        return $collection->toArray();
    }
}
