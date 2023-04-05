<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface as IShopRepository;
use SimiCart\SimpifyManagement\Model\Source\AuthMode;

/**
 * Authenticate and install shop
 */
class InstallShop
{
    protected IShopRepository $shopRepository;
    protected ConfigProvider $configProvider;

    /**
     * @param IShopRepository $shopRepository
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        IShopRepository $shopRepository,
        ConfigProvider $configProvider
    ) {
        $this->shopRepository = $shopRepository;
        $this->configProvider = $configProvider;
    }

    /**
     * Execution.
     *
     * @param string $shopDomain
     * @param string|null $code
     * @return array|void
     */
    public function execute(string $shopDomain, ?string $code = null)
    {
        try {
            $shop = $this->shopRepository->getByDomain($shopDomain);
            if (!$shop->getId()) {
                $this->createShop($shopDomain);
                $shop = $this->shopRepository->getByDomain($shopDomain);
            }
            $grantMode = $shop->hasOfflineAccess() ? $this->configProvider->getApiGrantMode() : AuthMode::OFFLINE;

            // If there's no code
            if (empty($code)) {
                return [
                    'completed' => false,
                    'url' => $shop->getShopApi()->buildAuthUrl($grantMode, $this->configProvider->getApiScopes()),
                    'shop_id' => $shop->getId(),
                ];
            }

            // if the store has been deleted, restore the store to set the access token
            if ($shop->hasUninstalled()) {
                $shop->restore();
            }
            if (!$shop->hasOfflineAccess()) {
                // Get the data and set the access token
                $data = $shop->getShopApi()->getAccessData($code);
                $shop->setAccessToken($data['access_token']);
            }
            $storeData = $shop->getShopApi()->getShopInfo();
            if (isset($storeData['shop'])) {
                $shop->setShopName($storeData['shop']['name'] ?? $shop->getShopName());
                $shop->setShopEmail($storeData['shop']['email'] ?? $shop->getShopEmail());
            }
            $this->shopRepository->save($shop);
            return [
                'completed' => true,
                'url' => null,
                'shop_id' => $shop->getId(),
            ];
        } catch (\Exception $e) {
            // Just return the default setting
            return [
                'completed' => false,
                'url' => null,
                'shop_id' => null,
            ];
        }
    }

    /**
     * Create not shop using shop domain
     *
     * @param string $shopDomain
     * @param string|null $token
     * @throws CouldNotSaveException
     */
    protected function createShop(string $shopDomain, ?string $token = null)
    {
        $shopData = [
            ShopInterface::SHOP_NAME => $shopDomain,
            ShopInterface::SHOP_DOMAIN => $shopDomain,
            ShopInterface::SHOP_EMAIL => "shop@$shopDomain",
            ShopInterface::SHOP_ACCESS_TOKEN => $token ?? "",
        ];

        $this->shopRepository->createShop($shopData);
    }
}
