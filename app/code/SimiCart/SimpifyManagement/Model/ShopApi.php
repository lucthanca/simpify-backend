<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\UrlInterface as IUrl;
use SimiCart\SimpifyManagement\Api\ShopApiInterface as IShopAPI;
use SimiCart\SimpifyManagement\Model\Clients\RestFactory as FRest;
use SimiCart\SimpifyManagement\Model\Clients\Rest;
use SimiCart\SimpifyManagement\Model\Source\AuthMode;

class ShopApi implements IShopAPI
{
    protected Rest $client;
    protected IUrl $IUrl;

    /**
     * @param FRest $clientFactory
     * @param IUrl $IUrl
     * @param string|null $shopDomain
     * @param array $options
     */
    public function __construct(
        FRest $clientFactory,
        IUrl $IUrl,
        ?string $shopDomain = null,
        array $options = []
    ) {
        $this->client = $clientFactory->create(['shopDomain' => $shopDomain, 'options' => $options]);
        $this->IUrl = $IUrl;
    }

    /**
     * @inheritDoc
     */
    public function buildAuthUrl(int $authMode, string $scopes): string
    {
        // authMode inString
        $mode = AuthMode::toNative($authMode);
        return $this->client->getAuthUrl(
            $scopes,
            $this->IUrl->getUrl('simpify/authenticate', ['secure' => true]),
            strtolower($mode)
        );
    }

    /**
     * @inheirtDoc
     */
    public function getAccessData(string $code): array
    {
        return $this->client->requestAccess($code);
    }

    public function getShopInfo(): array
    {
        return $this->client->getShopInfo();
    }
}
