<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\Adminhtml\Theme\Helper\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Registry;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface as ITheme;

class ImagePreview extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery
{
    protected $name = 'preview_images';
    protected $formName = 'theme_form';
    protected $fieldNameSuffix = null;
    protected $htmlId = 'preview_images';
    private ?DataPersistorInterface $dataPersistor;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Registry $registry,
        \Magento\Framework\Data\Form $form,
        $data = [],
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context, $storeManager, $registry, $form, $data, $dataPersistor);
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Get product images
     *
     * @return array|null
     */
    public function getImages()
    {
        $images = $this->getDataObject()->getData(ITheme::PREVIEW_IMAGES) ?: null;
        if ($images === null) {
            $images = ((array)$this->dataPersistor->get('simpify_theme'))[ITheme::PREVIEW_IMAGES] ?? null;
        }

        if ($images === null) {
            return null;
        }

        $serializer = new \Magento\Framework\Serialize\Serializer\Json();
        return is_array($images) ? $images : $serializer->unserialize($images);
    }

    /**
     * @inheritDoc
     */
    public function getDataObject()
    {
        return $this->registry->registry('current_theme');
    }
}
