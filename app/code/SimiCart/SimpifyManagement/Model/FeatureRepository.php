<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\TemporaryState\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\Data\FeatureInterface as IFeature;
use SimiCart\SimpifyManagement\Model\FeatureFactory as FFeature;
use SimiCart\SimpifyManagement\Api\FeatureRepositoryInterface;
use \SimiCart\SimpifyManagement\Model\ResourceModel\Feature as FeatureResource;

class FeatureRepository implements FeatureRepositoryInterface
{
    protected FeatureFactory $featureFactory;
    protected FeatureResource $featureResource;
    protected \Psr\Log\LoggerInterface $logger;

    /**
     * Feature repository constructor
     *
     * @param LoggerInterface $logger
     * @param FeatureFactory $featureFactory
     * @param FeatureResource $featureResource
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        FFeature $featureFactory,
        FeatureResource $featureResource
    ) {
        $this->logger = $logger;
        $this->featureFactory = $featureFactory;
        $this->featureResource = $featureResource;
    }

    /**
     * Create emoty feature
     *
     * @return IFeature
     */
    public function getNewEmptyItem(): IFeature
    {
        return $this->featureFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): IFeature
    {
        try {
            $f = $this->getNewEmptyItem();
            $this->featureResource->load($f, $id);
            return $f;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new NoSuchEntityException(__($e->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    public function save(IFeature $feature): IFeature
    {
        try {
            $this->featureResource->save($feature);
            return $feature;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(IFeature $feature): void
    {
        try {
            $this->featureResource->delete($feature);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new CouldNotDeleteException(__("Could not delete Feature."));
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): void
    {
        $feature = $this->getById($id);
        $this->delete($feature);
    }
}
