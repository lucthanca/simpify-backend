<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface FeatureFieldRepositoryInterface
{
    /**
     * Get feature config field by id
     *
     * @param int $id
     * @return Data\FeatureFieldInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): Data\FeatureFieldInterface;

    /**
     * Save feature field
     *
     * @param Data\FeatureFieldInterface $featureField
     * @return Data\FeatureFieldInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\FeatureFieldInterface $featureField): Data\FeatureFieldInterface;
}
