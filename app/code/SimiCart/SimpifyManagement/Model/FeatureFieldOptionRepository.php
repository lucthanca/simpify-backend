<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\FeatureFieldOptionRepositoryInterface;
use SimiCart\SimpifyManagement\Api\Data\FieldOptionSearchResultsInterface;
use SimiCart\SimpifyManagement\Api\Data\FeatureFieldOptionInterface as IFeatureFieldOption;
use SimiCart\SimpifyManagement\Api\Data\FieldOptionSearchResultsInterfaceFactory as FieldOptionSearchResultsFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\FeatureFieldOption as FeatureFieldOptionResource;
use SimiCart\SimpifyManagement\Model\ResourceModel\FeatureFieldOption\CollectionFactory as OptionFieldCollectionFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\FeatureFieldOption\Collection as FeatureFieldOptionCollection;
use Magento\Framework\Api\SearchCriteriaBuilder;
use SimiCart\SimpifyManagement\Model\FeatureFieldOptionFactory;

class FeatureFieldOptionRepository implements FeatureFieldOptionRepositoryInterface
{
    protected OptionFieldCollectionFactory $collectionFactory;
    protected CollectionProcessorInterface $collectionProcessor;
    protected FieldOptionSearchResultsFactory $searchResultsFactory;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected LoggerInterface $logger;
    protected FeatureFieldOptionFactory $featureFieldOptionFactory;
    protected FeatureFieldOptionResource $featureFieldOptionResource;

    /**
     * FeatureFieldOptionRepository constructopr
     *
     * @param LoggerInterface $logger
     * @param OptionFieldCollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param FieldOptionSearchResultsFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FeatureFieldOptionFactory $featureFieldOptionFactory
     * @param FeatureFieldOptionResource $featureFieldOptionResource
     */
    public function __construct(
        LoggerInterface                 $logger,
        OptionFieldCollectionFactory    $collectionFactory,
        CollectionProcessorInterface    $collectionProcessor,
        FieldOptionSearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder           $searchCriteriaBuilder,
        FeatureFieldOptionFactory $featureFieldOptionFactory,
        FeatureFieldOptionResource $featureFieldOptionResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
        $this->featureFieldOptionFactory = $featureFieldOptionFactory;
        $this->featureFieldOptionResource = $featureFieldOptionResource;
    }

    /**
     * @inheritDoc
     */
    public function getByFieldId(int $fieldId): FieldOptionSearchResultsInterface
    {
        try {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(IFeatureFieldOption::FIELD_ID, $fieldId)
                ->create();
            return $this->getList($searchCriteria);
        } catch (Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(__("Can not get list option of provided field"));
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): FieldOptionSearchResultsInterface
    {
        /** @var FeatureFieldOptionCollection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var FieldOptionSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Create new instance
     *
     * @return IFeatureFieldOption
     */
    public function newEmptyInstance(): IFeatureFieldOption
    {
        return $this->featureFieldOptionFactory->create();
    }

    /**
     * Get option by field and value
     *
     * @param int $fieldId
     * @param string $value
     * @return IFeatureFieldOption
     * @throws NoSuchEntityException
     */
    public function getByFieldValue(int $fieldId, string $value): IFeatureFieldOption
    {
        try {
            $fo = $this->newEmptyInstance();
            $this->featureFieldOptionResource->loadByValueAndField($fo, $value, $fieldId);
            return $fo;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new NoSuchEntityException(__("Can not get field option."));
        }
    }
}
