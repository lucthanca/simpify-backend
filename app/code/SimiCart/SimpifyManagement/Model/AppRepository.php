<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use SimiCart\SimpifyManagement\Api\AppLayoutRepositoryInterface;
use SimiCart\SimpifyManagement\Api\AppRepositoryInterface as IAppRepository;
use SimiCart\SimpifyManagement\Api\Data;
use SimiCart\SimpifyManagement\Model\AppFactory;
use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;
use \SimiCart\SimpifyManagement\Model\ResourceModel\App as AppResource;

class AppRepository implements IAppRepository
{
    protected AppFactory $appFactory;
    protected AppResource $appRes;
    protected AppLayoutRepositoryInterface $appLayoutRepository;

    /**
     * @param AppFactory $appFactory
     * @param AppResource $appRes
     * @param AppLayoutRepositoryInterface $appLayoutRepository
     */
    public function __construct(
        AppFactory $appFactory,
        AppResource $appRes,
        AppLayoutRepositoryInterface $appLayoutRepository
    ) {
        $this->appFactory = $appFactory;
        $this->appRes = $appRes;
        $this->appLayoutRepository = $appLayoutRepository;
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
}
