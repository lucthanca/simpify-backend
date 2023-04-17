<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model;

use Magento\Authorization\Model\CompositeUserContext as CoreCompositeUserContext;

class CompositeUserContext extends CoreCompositeUserContext implements ShopContextInterface
{

    /**
     * @inheritDoc
     */
    public function getSimpifyShopId(): ?int
    {
        if ($this->getUserContext() && method_exists($this->getUserContext(), "getSimpifyShopId")) {
            return $this->getUserContext()->getSimpifyShopId();
        }
        return null;
    }
}
