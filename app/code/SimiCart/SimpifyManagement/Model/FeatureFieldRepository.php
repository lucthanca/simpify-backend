<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\Data;
use SimiCart\SimpifyManagement\Api\FeatureFieldRepositoryInterface;
use SimiCart\SimpifyManagement\Model\FeatureFieldFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\FeatureField as FeatureFieldResource;

class FeatureFieldRepository implements FeatureFieldRepositoryInterface
{
    protected FeatureFieldFactory $featureFieldFactory;
    protected \Psr\Log\LoggerInterface $logger;
    protected FeatureFieldResource $featureFieldResource;

    /**
     * Constructor
     *
     * @param FeatureFieldFactory $featureFieldFactory
     * @param LoggerInterface $logger
     * @param FeatureFieldResource $featureResource
     */
    public function __construct(
        FeatureFieldFactory $featureFieldFactory,
        \Psr\Log\LoggerInterface $logger,
        FeatureFieldResource $featureResource
    ) {
        $this->featureFieldFactory = $featureFieldFactory;
        $this->logger = $logger;
        $this->featureFieldResource = $featureResource;
    }

    /**
     * Create new empty instance
     *
     * @return Data\FeatureFieldInterface
     */
    public function newEmptyInstance(): Data\FeatureFieldInterface
    {
        return $this->featureFieldFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): Data\FeatureFieldInterface
    {
        try {
            $ff = $this->newEmptyInstance();
            $this->featureFieldResource->load($ff, $id);
            return $ff;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new NoSuchEntityException(__("Can not get Feature Config Field."));
        }
    }

    /**
     * @inheritDoc
     */
    public function save(Data\FeatureFieldInterface $featureField): Data\FeatureFieldInterface
    {
        try {
            $this->featureFieldResource->save($featureField);
            return $featureField;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new CouldNotSaveException(__("Can not save Feature Field."));
        }
    }

    /**
     * Delete feature field
     *
     * @param Data\FeatureFieldInterface $featureField
     * @return FeatureFieldResource
     * @throws \Exception
     */
    public function delete(Data\FeatureFieldInterface $featureField)
    {
        return $this->featureFieldResource->delete($featureField);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): void
    {
        try {
            $ff = $this->newEmptyInstance();
            $this->featureFieldResource->load($ff, $id);

            $this->delete($ff);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new CouldNotDeleteException(__("Can not delete the feature field."));
        }
    }
}
