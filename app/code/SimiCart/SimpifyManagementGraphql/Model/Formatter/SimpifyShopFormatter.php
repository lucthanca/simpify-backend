<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Formatter;

use SimiCart\SimpifyManagement\Helper\UtilTrait;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;

class SimpifyShopFormatter
{
    use UtilTrait;

    /**
     * Format simpify shop to graphql output
     *
     * @param IShop $shop
     * @return array
     */
    public function formatToOutput(IShop $shop): array
    {
        $baseData = $shop->getData();
        // process model and uid
        $baseData['model'] = $shop;
        $baseData['uid'] = $this->base64UrlEncode($shop->getShopDomain());

        // Other data
        $baseData['more_info'] = $shop->getArrayMoreInfo();
        return $baseData;
    }
}
