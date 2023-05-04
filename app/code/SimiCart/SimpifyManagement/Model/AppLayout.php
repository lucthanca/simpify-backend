<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use SimiCart\SimpifyManagement\Api\AppRepositoryInterface;
use SimiCart\SimpifyManagement\Api\Data\AppInterface;
use SimiCart\SimpifyManagement\Api\Data\AppLayoutInterface as IAppLayout;
use SimiCart\SimpifyManagement\Model\ResourceModel\AbstractResource;
use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;

class AppLayout extends AbstractModel implements IAppLayout
{
    protected Json $jsonSerializer;

    protected ?IApp $app;
    protected AppRepositoryInterface $appRepository;

    /**
     * App layout constructor
     *
     * @param AppRepositoryInterface $appRepository
     * @param Json $jsonSerializer
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        AppRepositoryInterface $appRepository,
        Json $jsonSerializer,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->jsonSerializer = $jsonSerializer;
        $this->appRepository = $appRepository;
    }

    /**
     * Init app layoput model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\AppLayout::class);
    }

    // Implement getThemeId and setThemeId
    /**
     * @inheritDoc
     */
    public function getThemeId(): int
    {
        return (int) $this->getData(self::THEME_ID);
    }

    /**
     * @inheritDoc
     */
    public function setThemeId(int $id): IAppLayout
    {
        return $this->setData(self::THEME_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getAppId(): int
    {
        return (int) $this->getData(self::APP_ID);
    }

    /**
     * @inheritDoc
     */
    public function setAppId(int $id): IAppLayout
    {
        return $this->setData(self::APP_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getFont(): string
    {
        return $this->getData(self::FONT);
    }

    /**
     * @inheritDoc
     */
    public function setFont(?string $fontId): IAppLayout
    {
        return $this->setData(self::FONT, $fontId);
    }

    /**
     * @inheritDoc
     */
    public function setLayoutFont(string $fontName): IAppLayout
    {
        return $this->setData(self::FONT, $fontName);
    }

    /**
     * @inheritDoc
     */
    public function getColors(): string
    {
        return $this->getData(self::COLORS);
    }

    /**
     * @inheritDoc
     */
    public function getDecodedColors(): array
    {
        $raw = $this->getColors();
        if ($raw) {
            return $this->jsonSerializer->unserialize($raw);
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function setColors(?array $colors): IAppLayout
    {
        $encoded = $this->jsonSerializer->serialize($colors);
        return $this->setData(self::COLORS, $encoded);
    }

    /**
     * @inheritDoc
     */
    public function getMenu(): string
    {
        return $this->getData(self::MENU);
    }

    /**
     * @inheritDoc
     */
    public function setMenu($data): IAppLayout
    {
        if (is_array($data)) {
            $data = $this->jsonSerializer->serialize($data);
        }
        return $this->setData(self::MENU, $data);
    }

    /**
     * @inheritDoc
     */
    public function getHomePage(): string
    {
        return $this->getData(self::HOMEPAGE);
    }

    /**
     * @inheritDoc
     */
    public function setHomepage(?string $data): IAppLayout
    {
        return $this->setData(self::HOMEPAGE, $data);
    }

    /**
     * @inheritDoc
     */
    public function getCollectionPage(): string
    {
        return $this->getData(self::COLLECTION_PAGE);
    }

    /**
     * @inheritDoc
     */
    public function setCollectionPage(?string $data): IAppLayout
    {
        return $this->setData(self::COLLECTION_PAGE, $data);
    }

    /**
     * @inheritDoc
     */
    public function getProductPage(): string
    {
        return $this->getData(self::PRODUCT_PAGE);
    }

    /**
     * @inheritDoc
     */
    public function setProductPage(?string $data): IAppLayout
    {
        return $this->setData(self::PRODUCT_PAGE, $data);
    }

    /**
     * @inheritDoc
     */
    public function getLandingPage(): string
    {
        return $this->getData(self::LANDING_PAGE);
    }

    /**
     * @inheritDoc
     */
    public function setLandingPage(?string $data): IAppLayout
    {
        return $this->setData(self::LANDING_PAGE, $data);
    }

    /**
     * @inheritDoc
     */
    public function getApp(): AppInterface
    {
        if (!$this->app) {
            $this->app = $this->appRepository->getByLayoutId((int) $this->getId());
        }

        return $this->app;
    }
}
