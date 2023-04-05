<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\Clients;

use Magento\Framework\DataObject;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;

/**
 * Required options for Client
 *
 * @method string|null getApiKey
 * @method string setApiKey(string $key)
 * @method string|null getApiSecret
 * @method string|null getAccessToken
 * @method IShop getShop
 */
class ClientOptions extends DataObject
{
}
