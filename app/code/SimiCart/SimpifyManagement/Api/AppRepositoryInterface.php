<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use SimiCart\SimpifyManagement\Api\Data\AppSearchResultsInterface;

interface AppRepositoryInterface
{
    /**
     * Get app by related app layout id
     *
     * @param int $lId
     * @return Data\AppInterface
     */
    public function getByLayoutId(int $lId): Data\AppInterface;

    /**
     * Get list app by search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return AppSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): AppSearchResultsInterface;
}
