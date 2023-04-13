<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\FeatureField\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use SimiCart\SimpifyManagement\Api\FeatureFieldOptionRepositoryInterface;
use SimiCart\SimpifyManagement\Model\ResourceModel\FeatureField\CollectionFactory;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldInterface as IFeatureField;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class DataProvider extends AbstractDataProvider
{
    protected ?array $loadedData = null;
    protected ContextInterface $context;
    protected FeatureFieldOptionRepositoryInterface $fieldOptionRepository;

    /**
     * DataProvider constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param ContextInterface $context
     * @param FeatureFieldOptionRepositoryInterface $fieldOptionRepository
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ContextInterface $context,
        FeatureFieldOptionRepositoryInterface $fieldOptionRepository,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->context = $context;
        $this->fieldOptionRepository = $fieldOptionRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get sub-user data
     *
     * @return array
     */
    public function getData(): array
    {
        if (null !== $this->loadedData) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /** @var IFeatureField $item */
        foreach ($items as $item) {
            $id = $item->getId();
            $this->loadedData[$id] = $item->getData();
            $this->loadedData[$id]['is_disabled'] = true;
            $this->loadedData[$id]['options'] = [];

            $optionSearchResults = $this->fieldOptionRepository->getByFieldId((int) $id);
            foreach ($optionSearchResults->getItems() as $fieldOption) {
                $this->loadedData[$id]['options'][] = $fieldOption->getData();
            }
        }
        $this->loadedData[''] = $this->getDefaultData();
        return $this->loadedData;
    }

    /**
     * Get default customer data for adding new config field
     *
     * @return array
     */
    private function getDefaultData(): array
    {
        return [
            IFeatureField::FEATURE_ID => $this->context->getRequestParam('feature_id')
        ];
    }
}
