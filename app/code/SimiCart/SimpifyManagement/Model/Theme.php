<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;

class Theme extends AbstractModel implements ThemeInterface
{
    const BASE_PREVIEW_IMAGES_PATH = 'simpify/theme/images';

    protected $_eventPrefix = 'simpify_theme';
    private StoreManagerInterface $storeManager;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @throws FileSystemException
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
        $this->dir = $filesystem->getDirectoryWrite(DirectoryList::PUB);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Theme::class);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(?string $name): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getImage(): string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setImage(?string $image): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * @inheritDoc
     */
    public function getPreviewImages(): string
    {
        return $this->getData(self::PREVIEW_IMAGES);
    }

    /**
     * @inheritDoc
     */
    public function setPreviewImages(?string $previewImages): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::PREVIEW_IMAGES, $previewImages);
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
    public function setCreatedAt(?string $createdAt): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(?string $updatedAt): \SimiCart\SimpifyManagement\Api\Data\ThemeInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get preview images as array
     *
     * @return array
     */
    public function getPreviewImagesAsArray(): array
    {
        $previewImages = $this->getPreviewImages();
        $result = [];
        if ($previewImages) {
            $serializer = new \Magento\Framework\Serialize\Serializer\Json();
            $images = $serializer->unserialize($previewImages);
            foreach ($images as $image) {
                if ((bool) $image['disabled'] === true) {
                    continue;
                }
                $result[] = [
                    'url' => $this->getPreviewImagUrl($image['file']),
                    'position' => $image['position'] ?? 0,
                    'label' => $image['label'] ?? '',
                ];
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getPreviewImagUrl($file)
    {
        return $this->getBasePreviewImageUrl() . '/' . $this->_prepareFile($file);
    }

    public function getBasePreviewImageUrl()
    {
        return $this->storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::BASE_PREVIEW_IMAGES_PATH;
    }

    /**
     * Process file path.
     *
     * @param string $file
     * @return string
     */
    protected function _prepareFile($file)
    {
        return ltrim(str_replace('\\', '/', $file), '/');
    }

    /**
     * Get theme images
     *
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl(): ?string
    {
        $image = $this->getImage();
        if ($image) {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
            return rtrim($baseUrl, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($image, DIRECTORY_SEPARATOR);
        }
        return null;
    }

    /**
     * Get theme image size in bytes
     *
     * @return int
     */
    public function getThemeImageSize(): int
    {
        if ($image = $this->getImage()) {
            $imagePath = $this->dir->getAbsolutePath($image);
            if ($this->dir->isExist($imagePath)) {
                $imageSize = $this->dir->stat($imagePath);
                return (int) $imageSize['size'];
            }
        }
        return 0;
    }
}
