<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\Adminhtml\Theme\Helper\Form\ImagePreview;

use Magento\Backend\Block\DataProviders\ImageUploadConfig as ImageUploadConfigDataProvider;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\MediaStorage\Helper\File\Storage\Database;

class Content extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery\Content
{
    protected $_template = 'SimiCart_SimpifyManagement::form/theme/preview-image.phtml';

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
}
