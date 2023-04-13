<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface FieldOptionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return FeatureFieldOptionInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param FeatureFieldOptionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
