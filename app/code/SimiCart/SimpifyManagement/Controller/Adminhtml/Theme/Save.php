<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Theme;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Model\Theme;
use SimiCart\SimpifyManagement\Model\ThemeFactory;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected $mediaDirectory;
    protected $logger;
    private \SimiCart\SimpifyManagement\Model\ThemeFactory $themeFactory;
    private \Magento\Framework\Filesystem\Directory\WriteInterface $pubDirectory;
    private StoreManagerInterface $storeManager;

    public const ADMIN_RESOURCE = 'SimiCart_SimpifyManagement::theme';

    /**
     * @var \Magento\Catalog\Model\ImageUploader
     */
    private $imageUploader;

    /**
     * @param ThemeFactory $themeFactory
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     * @param null $imageUploader
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function __construct(
        \SimiCart\SimpifyManagement\Model\ThemeFactory $themeFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        Context $context,
        ImageUploader $imageUploader = null
    ) {
        parent::__construct($context);
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->pubDirectory = $filesystem->getDirectoryWrite(DirectoryList::PUB);
        $this->logger = $logger;
        $this->themeFactory = $themeFactory;
        $this->storeManager = $storeManager;
        if (!$imageUploader) {
            throw new LocalizedException(__("Image uploader not defined!"));
        }
        $this->imageUploader = $imageUploader;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $post = $this->getRequest()->getPost();
        if (empty($post['name'])) {
            $this->messageManager->addErrorMessage(__('Theme name is required.'));
            return $this->_redirect('*/*/');
        }
        $theme = $this->themeFactory->create();

        if (!empty($post['entity_id'])) {
            // load existed theme using theme resource model
            $theme->getResource()->load($theme, $post['entity_id']);
        }
        // set theme name
        $theme->setName($post['name']);
        // set theme status
        $theme->setStatus((int) $post['status']);
        // set theme colors
        $theme->setColors($post['colors']);

        try {
            $theme->setImage($this->saveThemeImage());
            $previewImages = $this->saveImagePreviews();
            // encode preview images data to json
            $serializer = new \Magento\Framework\Serialize\Serializer\Json();
            $theme->setPreviewImages($serializer->serialize($previewImages));
            // save theme
            $theme->save();
            $this->messageManager->addSuccessMessage(__('Theme has been saved.'));
        } catch (LocalizedException|FileSystemException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the theme.'));
        }
        return $this->_redirect('*/*/');
    }

    /**
     * Save theme image
     *
     * @return string|null
     */
    protected function saveThemeImage(): ?string
    {
        $value = $this->getRequest()->getPost('image');
        if (!is_array($value) || empty($value)) {
            return null;
        }
        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                $store = $this->storeManager->getStore();
                $baseMediaDir = $store->getBaseMediaDir();
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageName, true);
                $value[0]['url'] = '/' . $baseMediaDir . '/' . $newImgRelativePath;
                $value[0]['name'] = $value[0]['url'];
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        } elseif (true) { // $this->fileResidesOutsideCategoryDir($value)
            // use relative path for image attribute so we know it's outside of category dir when we fetch it
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
            $value[0]['name'] = $value[0]['url'];
        }
        return $this->getUploadedImageName($value);
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable($value)
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    /**
     * Gets image name from $value array.
     *
     * Will return empty string in a case when $value is not an array.
     *
     * @param array $value Attribute value
     * @return string
     */
    private function getUploadedImageName($value)
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }

    /**
     * Save image previews
     *
     * @return array
     * @throws LocalizedException
     * @throws FileSystemException
     * @since 101.0.0
     */
    protected function saveImagePreviews(): array
    {
        $value = $this->getRequest()->getPost('preview_images');
        if (!is_array($value) || !isset($value['images'])) {
            return [];
        }
        if (!is_array($value['images'])) {
            $value['images'] = [];
        }
        $clearImages = [];
        try {
            foreach ($value['images'] as &$image) {
                if (!empty($image['removed'])) {
                    $clearImages[] = $image['file'];
                } elseif (empty($image['value_id']) || !empty($image['recreate'])) {
                    $newFile = $this->moveImageFromTmp($image['file']);
                    $image['new_file'] = $newFile;
                    $image['file'] = $newFile;
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(__('Something went wrong while saving the image(s) data.'));
        }

        // process delete images
        if (!empty($clearImages)) {
            foreach ($clearImages as $image) {
                try {
                    $this->mediaDirectory->delete($this->getMediaPath($image['file']));
                } catch (\Exception $e) {
                    // do nothing in case image file doesn't exist. just log it.
                    $this->logger->critical($e);
                }
            }
        }

        // process images data for storage
        $images = [];
        foreach ($value['images'] as $key => &$image) {
            if (empty($image['value_id'])) {
                $image['value_id'] = time() . '.' . $key;
            }
            $images[] = $image;
        }
        return $images;
    }


    /**
     * Move image from temporary directory to normal
     *
     * @param string $file
     * @return string
     * @throws FileSystemException
     * @since 101.0.0
     */
    protected function moveImageFromTmp($file)
    {
        $file = $this->getFilenameFromTmp($this->getSafeFilename($file));
        $destinationFile = $this->getUniqueFileName($file);

        $this->mediaDirectory->renameFile(
            $this->getTmpMediaPath($file),
            $this->getMediaPath($destinationFile)
        );

        return str_replace('\\', '/', $destinationFile);
    }

    /**
     * Check whether file to move exists. Getting unique name
     *
     * @param string $file
     * @param bool $forTmp
     * @return string
     * @since 101.0.0
     */
    protected function getUniqueFileName($file, $forTmp = false)
    {
        if (false) {
            // db storage temporary disabled
//            $destFile = $this->fileStorageDb->getUniqueFilename(
//                $this->mediaConfig->getBaseMediaUrlAddition(),
//                $file
//            );
        } else {
            $destinationFile = $forTmp
                ? $this->mediaDirectory->getAbsolutePath($this->getTmpMediaPath($file))
                : $this->mediaDirectory->getAbsolutePath($this->getMediaPath($file));
            // phpcs:disable Magento2.Functions.DiscouragedFunction
            $destFile = dirname($file) . '/' . FileUploader::getNewFileName($destinationFile);
        }

        return $destFile;
    }

    /**
     * Returns file name according to tmp name
     *
     * @param string $file
     * @return string
     * @since 101.0.0
     */
    protected function getFilenameFromTmp($file)
    {
        return strrpos($file, '.tmp') == strlen($file) - 4 ? substr($file, 0, strlen($file) - 4) : $file;
    }

    /**
     * Returns safe filename for posted image
     *
     * @param string $file
     * @return string
     */
    private function getSafeFilename($file)
    {
        $file = DIRECTORY_SEPARATOR . ltrim($file, DIRECTORY_SEPARATOR);

        return $this->mediaDirectory->getDriver()->getRealPathSafety($file);
    }

    /**
     * Get path to the temporary media.
     *
     * @param string $file
     * @return string
     */
    public function getTmpMediaPath($file)
    {
        return $this->getBaseTmpMediaPath() . '/' . $this->_prepareFile($file);
    }

    /**
     * Retrieve file system path for media file
     *
     * @param string $file
     * @return string
     */
    public function getMediaPath($file)
    {
        return $this->getBasePath() . '/' . $this->_prepareFile($file);
    }

    /**
     * Get the base temporary media path.
     *
     * @return string
     */
    protected function getBaseTmpMediaPath(): string
    {
        return 'tmp/' . $this->getBasePath();
    }

    /**
     * Get the base media path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return Theme::BASE_PREVIEW_IMAGES_PATH;
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
}
