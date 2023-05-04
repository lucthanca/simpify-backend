<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Name;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class AppImageUploader extends \Magento\Catalog\Model\ImageUploader
{
    protected $baseTmpPath = 'simpify/tmp/app';
    protected $basePath = 'simpify/app';
    private UploaderFactory $uploaderFactory;
    /**
     * @var array|mixed|string[]
     */
    private $allowedMimeTypes;

    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        $baseTmpPath,
        $basePath,
        $allowedExtensions,
        $allowedMimeTypes = [],
        Name $fileNameLookup = null
    ) {
        parent::__construct(
            $coreFileStorageDatabase,
            $filesystem,
            $uploaderFactory,
            $storeManager,
            $logger,
            $baseTmpPath,
            $basePath,
            $allowedExtensions,
            $allowedMimeTypes,
            $fileNameLookup
        );
        $this->uploaderFactory = $uploaderFactory;
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     * @return string[]
     *
     * @throws LocalizedException
     */
    public function saveFileToTmpDir($fileId)
    {
        $baseTmpPath = $this->getBaseTmpPath();

        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        if (!$uploader->checkMimeType($this->allowedMimeTypes)) {
            throw new LocalizedException(__('File validation failed.'));
        }
        $file = $uploader->validateFile();
        $newUniqueName = sprintf('%s.%s.%s', time(), uniqid(), $file['name']);
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath), $newUniqueName);
        unset($result['path']);

        if (!$result) {
            throw new LocalizedException(__('File can not be saved to the destination folder.'));
        }

        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).'),
                    $e
                );
            }
        }

        return $result;
    }

    public function getBasePath()
    {
        if ($this->additionalPath) {
            return parent::getBasePath() . DIRECTORY_SEPARATOR . $this->getAdditionalPath();
        }
        return parent::getBasePath();
    }

    /**
     * Set additional path for base path
     *
     * @param string $path
     * @return $this
     */
    public function setAdditionalPath(string $path)
    {
        $this->additionalPath = $path;
        return $this;
    }

    /**
     * Get additional path for base path
     *
     * @return string
     */
    public function getAdditionalPath()
    {
        return $this->additionalPath ?? '';
    }
}
