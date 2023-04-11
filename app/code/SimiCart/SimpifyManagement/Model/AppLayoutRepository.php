<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\Data;
use SimiCart\SimpifyManagement\Api\Data\AppLayoutInterface as IAppLayout;
use SimiCart\SimpifyManagement\Api\AppLayoutRepositoryInterface as IAppLayoutRepository;
use SimiCart\SimpifyManagement\Model\ResourceModel\AppLayout as AppLayoutResource;

class AppLayoutRepository implements IAppLayoutRepository
{
    protected LoggerInterface $logger;
    protected AppLayoutFactory $appLayoutFactory;
    protected AppLayoutResource $appLayoutRes;

    /**
     * Repository constructor
     *
     * @param LoggerInterface $logger
     * @param AppLayoutFactory $appLayoutFactory
     * @param AppLayoutResource $appLayoutRes
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        AppLayoutFactory $appLayoutFactory,
        AppLayoutResource $appLayoutRes
    ) {
        $this->logger = $logger;
        $this->appLayoutFactory = $appLayoutFactory;
        $this->appLayoutRes = $appLayoutRes;
    }

    /**
     * Create new instance of applayout
     *
     * @return IAppLayout
     */
    public function getInstance(): IAppLayout
    {
        return $this->appLayoutFactory->create();
    }

    /**
     * Load app layout by app id
     *
     * @param int $id
     * @return void
     * @throws NoSuchEntityException
     */
    public function getByAppId(int $id): IAppLayout
    {
        try {
            $appLayout = $this->getInstance();
            $this->appLayoutRes->load($appLayout, $id, IAppLayout::APP_ID);
            return $appLayout;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new NoSuchEntityException(__($e->getMessage()));
        }
    }

    /**
     * @inheirtDoc
     */
    public function getById(int $id): Data\AppLayoutInterface
    {
        try {
            $appLayout = $this->getInstance();
            $this->appLayoutRes->load($appLayout, $id);
            return $appLayout;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new NoSuchEntityException(__($e->getMessage()));
        }
    }
}
