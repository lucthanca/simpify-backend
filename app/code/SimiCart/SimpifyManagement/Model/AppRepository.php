<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use SimiCart\SimpifyManagement\Api\AppLayoutRepositoryInterface;
use SimiCart\SimpifyManagement\Api\AppRepositoryInterface as IAppRepository;
use SimiCart\SimpifyManagement\Api\Data;
use SimiCart\SimpifyManagement\Model\AppFactory;
use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;
use SimiCart\SimpifyManagement\Model\ResourceModel\App as AppResource;
use SimiCart\SimpifyManagement\Model\ResourceModel\App\CollectionFactory as AppCollectionFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\App\Collection as AppCollection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use SimiCart\SimpifyManagement\Api\Data\AppSearchResultsInterface;
use SimiCart\SimpifyManagement\Api\Data\AppSearchResultsInterfaceFactory as AppSearchResultsFactory;

class AppRepository implements IAppRepository
{
    protected AppFactory $appFactory;
    protected AppResource $appRes;
    protected AppLayoutRepositoryInterface $appLayoutRepository;
    protected AppCollectionFactory $collectionFactory;
    protected CollectionProcessorInterface $collectionProcessor;
    protected AppSearchResultsFactory $searchResultsFactory;

    /**
     * @param AppFactory $appFactory
     * @param AppResource $appRes
     * @param AppLayoutRepositoryInterface $appLayoutRepository
     * @param AppCollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param AppSearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        AppFactory $appFactory,
        AppResource $appRes,
        AppLayoutRepositoryInterface $appLayoutRepository,
        AppCollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        AppSearchResultsFactory $searchResultsFactory
    ) {
        $this->appFactory = $appFactory;
        $this->appRes = $appRes;
        $this->appLayoutRepository = $appLayoutRepository;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Create instance
     *
     * @return IApp
     */
    public function getInstance(): IApp
    {
        return $this->appFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getByLayoutId(int $lId): Data\AppInterface
    {
        try {
            $app = $this->getInstance();
            $appLayout = $this->appLayoutRepository->getById($lId);
            $this->appRes->load($app, $appLayout->getAppId());
            return $app;
        } catch (\Exception $e) {
            throw new NoSuchEntityException(__($e->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): AppSearchResultsInterface
    {
        /** @var AppCollection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var AppSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
