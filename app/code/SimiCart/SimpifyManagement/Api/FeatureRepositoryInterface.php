<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\TemporaryState\CouldNotSaveException;
use SimiCart\SimpifyManagement\Api\Data\FeatureInterface as IFeature;

interface FeatureRepositoryInterface
{
    /**
     * Get feature by entity id
     *
     * @param int $id
     * @return IFeature
     * @throws NoSuchEntityException
     */
    public function getById(int $id): IFeature;

    /**
     * Save provided feature
     *
     * @param IFeature $feature
     * @return IFeature
     * @throws CouldNotSaveException
     */
    public function save(IFeature $feature): IFeature;
}
