<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use SimiCart\SimpifyManagement\Api\AppLayoutRepositoryInterface as IAppLayoutRepository;
use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;
use Magento\Framework\Model\AbstractModel;
use SimiCart\SimpifyManagement\Api\Data\AppLayoutInterface as IAppLayout;
use Magento\Framework\Model\ResourceModel\AbstractResource;

class App extends AbstractModel implements IApp
{
    protected ?IAppLayout $appLayout = null;
    protected IAppLayoutRepository $appLayoutRepository;

    /**
     * App constructor
     *
     * @param IAppLayoutRepository $appLayoutRepository
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        IAppLayoutRepository $appLayoutRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->appLayoutRepository = $appLayoutRepository;
    }

    /**
     * Init App model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\App::class);
    }

    /**
     * @inheritDoc
     */
    public function isSplashFull(): bool
    {
        $v = $this->getData(self::SPLASH_IS_FULL);
        return $v === '1';
    }

    /**
     * @inheritDoc
     */
    public function setSplashIsFull(bool $full): IApp
    {
        return $this->setData(self::SPLASH_IS_FULL, $full);
    }

    /**
     * @inheritDoc
     */
    public function getSplashBgColor(): string
    {
        return $this->getData(self::SPLASH_BG_COLOR);
    }

    /**
     * @inheritDoc
     */
    public function setSplashBgColor(string $color): IApp
    {
        return $this->setData(self::SPLASH_BG_COLOR, $color);
    }

    /**
     * @inheritDoc
     */
    public function getAppLogo(): string
    {
        return $this->getData(self::APP_LOGO);
    }

    /**
     * @inheritDoc
     */
    public function setAppLogo(string $path): self
    {
        return $this->setData(self::APP_LOGO, $path);
    }

    /**
     * @inheritDoc
     */
    public function getSplashImage(): string
    {
        return $this->getData(self::SPLASH_IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setSplashImage(string $path): IApp
    {
        return $this->setData(self::SPLASH_IMAGE, $path);
    }

    /**
     * @inheritDoc
     */
    public function getAppIcon(): string
    {
        return $this->getData(self::APP_ICON);
    }

    /**
     * @inheritDoc
     */
    public function setAppIcon(string $path): IApp
    {
        return $this->setData(self::APP_ICON, $path);
    }

    /**
     * @inheritDoc
     */
    public function getShopId(): int
    {
        return (int) $this->getData(self::SHOP_ID);
    }

    /**
     * @inheritDoc
     */
    public function setShopId(int $shopId): IApp
    {
        return $this->setData(self::SHOP_ID, $shopId);
    }

    /**
     * @inheritDoc
     */
    public function getIndustry(): ?string
    {
        return $this->getData(self::INDUSTRY);
    }

    /**
     * @inheritDoc
     */
    public function setIndustry(?string $value): IApp
    {
        return $this->setData(self::INDUSTRY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getAppLayout(): IAppLayout
    {
        if (!$this->appLayout) {
            $this->appLayout = $this->appLayoutRepository->getByAppId((int) $this->getId());
        }

        return $this->appLayout;
    }
}
