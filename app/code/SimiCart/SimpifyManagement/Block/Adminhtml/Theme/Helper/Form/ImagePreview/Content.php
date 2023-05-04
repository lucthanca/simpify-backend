<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\Adminhtml\Theme\Helper\Form\ImagePreview;

use Magento\Backend\Block\DataProviders\ImageUploadConfig as ImageUploadConfigDataProvider;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use SimiCart\SimpifyManagement\Model\Theme;

class Content extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery\Content
{
    protected $_template = 'SimiCart_SimpifyManagement::form/theme/preview-image.phtml';

    protected $imageHelper = null;

    /**
     * @var ImageUploadConfigDataProvider|mixed
     */
    private $imageUploadConfigDataProvider;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\Catalog\Model\Product\Media\Config $mediaConfig, array $data = [], ImageUploadConfigDataProvider $imageUploadConfigDataProvider = null, Database $fileStorageDatabase = null, ?JsonHelper $jsonHelper = null)
    {
        parent::__construct($context, $jsonEncoder, $mediaConfig, $data, $imageUploadConfigDataProvider, $fileStorageDatabase, $jsonHelper);
        $this->imageUploadConfigDataProvider = $imageUploadConfigDataProvider
            ?: ObjectManager::getInstance()->get(ImageUploadConfigDataProvider::class);
    }

    /**
     * @inheritDoc
     */
    public function getMediaAttributes()
    {
        return [];
    }

    /**
     * @inheirtDoc
     */
    protected function _prepareLayout()
    {
        $this->addChild(
            'uploader',
            \Magento\Backend\Block\Media\Uploader::class,
            ['image_upload_config_data' => $this->imageUploadConfigDataProvider]
        );

        $this->getUploader()->getConfig()->setUrl(
            $this->_urlBuilder->getUrl('simpify/theme_previewimage/upload')
        )->setFileField(
            'image'
        )->setFilters(
            [
                'images' => [
                    'label' => __('Images (.gif, .jpg, .png)'),
                    'files' => ['*.gif', '*.jpg', '*.jpeg', '*.png'],
                ],
            ]
        );

        $this->_eventManager->dispatch('simpify_theme_preview_images_prepare_layout', ['block' => $this]);
        return $this;
    }

    public function getImagesJson()
    {
        $value = $this->getElement()->getImages();
        if (is_array($value)) {
            $mediaDir = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $images = $this->sortImagesByPosition($value);
            foreach ($images as &$image) {
                $image['url'] = $this->getMediaUrl($image['file']);
//                if ($this->fileStorageDatabase->checkDbUsage() &&
//                    !$mediaDir->isFile($this->_mediaConfig->getMediaPath($image['file']))
//                ) {
//                    $this->fileStorageDatabase->saveFileToFilesystem(
//                        $this->_mediaConfig->getMediaPath($image['file'])
//                    );
//                }
                try {
                    $fileHandler = $mediaDir->stat($this->getMediaPath($image['file']));
                    $image['size'] = $fileHandler['size'];
                } catch (FileSystemException $e) {
                    $image['url'] = $this->getImageHelper()->getDefaultPlaceholderUrl('small_image');
                    $image['size'] = 0;
                    $this->_logger->warning($e);
                }
            }
            return $this->_jsonEncoder->encode($images);
        }
        return '[]';
    }

    /**
     * Returns image helper object.
     *
     * @return \Magento\Catalog\Helper\Image
     * @deprecated 101.0.3
     */
    private function getImageHelper()
    {
        if ($this->imageHelper === null) {
            $this->imageHelper = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Catalog\Helper\Image::class);
        }
        return $this->imageHelper;
    }

    /**
     * @inheritdoc
     */
    public function getMediaUrl($file)
    {
        return $this->getBaseMediaUrl() . '/' . $this->_prepareFile($file);
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
     * Retrieve file system path for media file
     *
     * @param string $file
     * @return string
     */
    public function getMediaPath($file)
    {
        return $this->getBaseMediaUrlAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * Get web-based directory path for product images relative to the media directory.
     *
     * @return string
     */
    public function getBaseMediaUrlAddition()
    {
        return Theme::BASE_PREVIEW_IMAGES_PATH;
    }

    /**
     * Retrieve base url for media files
     *
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $this->getBaseMediaUrlAddition();
    }

    /**
     * Sort images array by position key
     *
     * @param array $images
     * @return array
     */
    private function sortImagesByPosition($images)
    {
        $nullPositions = [];
        foreach ($images as $index => $image) {
            if ($image['position'] === null) {
                $nullPositions[] = $image;
                unset($images[$index]);
            }
        }
        if (is_array($images) && !empty($images)) {
            usort(
                $images,
                function ($imageA, $imageB) {
                    return ($imageA['position'] < $imageB['position']) ? -1 : 1;
                }
            );
        }
        return array_merge($images, $nullPositions);
    }
}
