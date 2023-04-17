<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model;

interface ShopContextInterface extends \Magento\Authorization\Model\UserContextInterface
{
    /**#@+
     * Shop type in context
     */
    const USER_TYPE_SIMPIFY_SHOP = 9;
    /**#@-*/

    /**
     * Identify current simpify shop ID.
     *
     * @return int|null
     */
    public function getSimpifyShopId(): ?int;
}
