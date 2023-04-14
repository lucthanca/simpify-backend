<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface;
use SimiCart\SimpifyManagement\Api\ShopApiInterface as IShopApi;

class Shop extends AbstractModel implements ShopInterface
{
    protected $_eventPrefix = 'simpify_shop';

    protected $_eventObject = 'shop';

    protected ShopApiFactory $shopApiFactory;

    protected ?IShopApi $api = null;
    private ConfigProvider $configProvider;

    /**
     * Shop constructor
     *
     * @param ShopApiFactory $shopApiFactory
     * @param ConfigProvider $configProvider
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        ShopApiFactory                                          $shopApiFactory,
        ConfigProvider                                          $configProvider,
        \Magento\Framework\Model\Context                        $context,
        \Magento\Framework\Registry                             $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection = null,
        array                                                   $data = []
    ) {
        $this->shopApiFactory = $shopApiFactory;
        $this->configProvider = $configProvider;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Init shop model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SimiCart\SimpifyManagement\Model\ResourceModel\Shop::class);
    }

    /**
     * Return api helper for shop
     *
     * @return IShopApi
     */
    public function getShopApi(): IShopApi
    {
        if (!$this->api) {
            $opts = [
                'api_key' => $this->configProvider->getApiKey(),
                'api_secret' => $this->configProvider->getApiSecret(),
                'access_token' => $this->getAccessToken(),
                'api_version' => $this->configProvider->getApiVersion(),
                'shop' => $this
            ];
            $this->api = $this->shopApiFactory->create([
                'shopDomain' => $this->getShopDomain(),
                'options' => $opts,
            ]);
        }
        return $this->api;
    }

    /**
     * Check if the shop has uninstalled app
     *
     * @return bool
     */
    public function hasUninstalled(): bool
    {
        return $this->getStatus() === static::STATUS_UNINSTALLED;
    }

    /**
     * Check if the shop has not completed install app
     *
     * @return bool
     */
    public function hasNotCompletedInstallation(): bool
    {
        return $this->getStatus() === static::STATUS_NOT_COMPLETED_INSTALLATION;
    }

    /**
     * Change status to installed
     *
     * @return ShopInterface
     */
    public function restore(): ShopInterface
    {
        return $this->setStatus(static::STATUS_INSTALLED);
    }

    public function install(string $shop, ?string $code)
    {

    }

    /**
     * Check if the access token is filled
     *
     * @return bool
     */
    public function hasOfflineAccess(): bool
    {
        return $this->getAccessToken() !== null && !empty($this->getAccessToken());
    }

    /**
     * Check if the storefront token is filled
     *
     * @return bool
     */
    public function hasStorefrontToken(): bool
    {
        return $this->getShopStorefrontToken() !== null && !empty($this->getShopStorefrontToken());
    }

    /**
     * @inheritDoc
     */
    public function getShopDomain(): string
    {
        return $this->getData(self::SHOP_DOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function setShopDomain(string $domain): ShopInterface
    {
        return $this->setData(self::SHOP_DOMAIN, $domain);
    }

    /**
     * @inheritDoc
     */
    public function getShopName(): ?string
    {
        return $this->getData(self::SHOP_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setShopName(?string $name): ShopInterface
    {
        return $this->setData(self::SHOP_NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getShopEmail(): ?string
    {
        return $this->getData(self::SHOP_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setShopEmail(?string $email): ShopInterface
    {
        return $this->setData(self::SHOP_EMAIL, $email);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status = 0): ShopInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getAppInfo(): string
    {
        return $this->getData(self::APP_INFO);
    }

    /**
     * @inheritDoc
     */
    public function setAppInfo($info): ShopInterface
    {
        if (is_array($info)) {
            $info = $this->convertToJson($info);
        }

        return $this->setData(self::APP_INFO, $info);
    }

    /**
     * @inheritDoc
     */
    public function getPlanId(): int
    {
        return (int)$this->getData(self::PLAN_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPlanId(int $id): ShopInterface
    {
        return $this->setData(self::PLAN_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $time): ShopInterface
    {
        return $this->setData(self::CREATED_AT, $time);
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(): ?string
    {
        return $this->getData(self::SHOP_ACCESS_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(?string $api): ShopInterface
    {
        return $this->setData(self::SHOP_ACCESS_TOKEN, $api);
    }

    /**
     * @inheritDoc
     */
    public function getShopStorefrontToken(): ?string
    {
        return $this->getData(self::SHOP_STOREFRONT_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setShopStorefrontToken(?string $token): self
    {
        return $this->setData(self::SHOP_STOREFRONT_TOKEN, $token);
    }

    /**
     * @inheritDoc
     */
    public function getSimiAccessToken(): ?string
    {
        return $this->getData(self::SIMI_ACCESS_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setSimiAccessToken(?string $api): ShopInterface
    {
        return $this->setData(self::SIMI_ACCESS_TOKEN, $api);
    }
}
