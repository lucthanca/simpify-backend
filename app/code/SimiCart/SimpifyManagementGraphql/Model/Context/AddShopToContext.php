<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Context;

use SimiCart\SimpifyManagementGraphql\Model\ShopContextInterface;
use Magento\GraphQl\Model\Query\ContextParametersInterface;
use Magento\GraphQl\Model\Query\ContextParametersProcessorInterface;

class AddShopToContext implements ContextParametersProcessorInterface
{
    private ShopContextInterface $shopContext;

    /**
     * @param ShopContextInterface $shopContext
     */
    public function __construct(
        ShopContextInterface $shopContext
    ) {
        $this->shopContext = $shopContext;
    }

    /**
     * @inheritDoc
     */
    public function execute(ContextParametersInterface $contextParameters): ContextParametersInterface
    {
        $userType = (int) $this->shopContext->getUserType();
        $shopId = (int) $this->shopContext->getSimpifyShopId();
        $contextParameters->addextensionAttribute(
            'is_simpify_shop',
            !empty($userType) &&
            !empty($shopId) &&
            $this->isSimpifyShop($userType)
        );
        $contextParameters->addExtensionAttribute('simpify_shop_id', $shopId);
        return $contextParameters;
    }

    /**
     * Check if authorized user type is simpify shop
     *
     * @param int $type
     * @return bool
     */
    private function isSimpifyShop(int $type): bool
    {
        return $type === ShopContextInterface::USER_TYPE_SIMPIFY_SHOP;
    }
}
