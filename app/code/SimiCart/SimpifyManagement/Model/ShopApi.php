<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\UrlInterface as IUrl;
use SimiCart\SimpifyManagement\Api\ShopApiInterface as IShopAPI;
use SimiCart\SimpifyManagement\Exceptions\UnhandledShopApiRequestFailed;
use SimiCart\SimpifyManagement\Model\Clients\RestFactory as FRest;
use SimiCart\SimpifyManagement\Model\Clients\Rest;
use SimiCart\SimpifyManagement\Model\Source\AuthMode;

class ShopApi implements IShopAPI
{
    protected Rest $client;
    protected IUrl $IUrl;

    protected \Magento\Framework\Stdlib\DateTime\DateTime $date;

    /**
     * @param FRest $clientFactory
     * @param IUrl $IUrl
     * @param string|null $shopDomain
     * @param array $options
     */
    public function __construct(
        FRest $clientFactory,
        IUrl $IUrl,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ?string $shopDomain = null,
        array $options = []
    ) {
        $this->date = $date;
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

    /**
     * @throws UnhandledShopApiRequestFailed
     */
    public function requestStorefrontToken(): string
    {
        try {
            $body = [
                'json' => [
                    'storefront_access_token' => [
                        'title' => "Generate Storefront Token at: {$this->date->gmtDate('Y-m-d H:i:s P')}"
                    ]
                ]
            ];
            $data = $this->client->request('POST', '/admin/api/{{api_version}}/storefront_access_tokens.json', $body);
            if (isset($data['storefront_access_token'])) {
                return $data['storefront_access_token']['access_token'];
            }

            $errorMessage = __("Something went wrong. Please contact us!");
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
        throw new UnhandledShopApiRequestFailed(__($errorMessage));
    }

    public function getShopInfo(): array
    {
        return $this->client->getShopInfo();
    }
}
