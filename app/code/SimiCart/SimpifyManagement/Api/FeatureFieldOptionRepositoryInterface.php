<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use SimiCart\SimpifyManagement\Api\Data\FieldOptionSearchResultsInterface;

interface FeatureFieldOptionRepositoryInterface
{
    /**
     * Get options data collection by given field id
     *
     * @param int $fieldId
     * @return FieldOptionSearchResultsInterface
     * @throws LocalizedException
     */
    public function getByFieldId(int $fieldId): FieldOptionSearchResultsInterface;

    /**
     * Load Option data collection by given search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return FieldOptionSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): FieldOptionSearchResultsInterface;
}
