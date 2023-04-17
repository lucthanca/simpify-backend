<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AppSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return AppInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param AppInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
