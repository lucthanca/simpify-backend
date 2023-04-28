<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\AppText;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

class Example implements ActionInterface
{
    const FILE_EXT = ".json";
    const MAIN_VAR_DIR = 'simpify_app_language';
    const FILE_NAME = 'example';

    protected ResponseInterface $response;
    protected LoggerInterface $logger;
    protected JsonFactory $jsonResultFactory;
    protected Filesystem\Directory\WriteInterface $dir;
    protected FileFactory $fileFactory;
    protected RequestInterface $request;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     * @param JsonFactory $jsonResultFactory
     * @param Filesystem $filesystem
     * @param FileFactory $fileFactory
     * @param RequestInterface $request
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        JsonFactory $jsonResultFactory,
        Filesystem $filesystem,
        FileFactory $fileFactory,
        RequestInterface $request
    ) {
        $this->response = $context->getResponse();
        $this->logger = $logger;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->dir = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->fileFactory = $fileFactory;
        $this->request = $request;
    }

    /**
     * Get response for action.
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get request for action.
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $this->getResponse()->setHeader(
            'Access-Control-Expose-Headers',
            'X-File-Size, Content-Encoding, Content-Length, Content-Type'
        );
        // Set header access-control-allow-headers with content-type, x-requested-with value
        $this->getResponse()->setHeader('Access-Control-Allow-Headers', 'Content-Type, x-requested-with');
        // Set header access-control-allow-origin with value *
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');

        // get hash from request
        $hash = $this->getRequest()->getParam('hash');

        // If hash exists in request then make $filePath using MAIN_VAR_DIR, hash and FILE_EXT, then register $downloadFileName with code param from request, if no code then using example as default
        try {
            if ($hash) {
                $filePath = self::MAIN_VAR_DIR . DIRECTORY_SEPARATOR . $hash . self::FILE_EXT;
                $downloadFileName = ($this->getRequest()->getParam('code') ?? self::FILE_NAME) . self::FILE_EXT;

                // register $content variable with two key type and value, with type is 'filename' and value is $filePath
                $content = [
                    'type' => 'filename',
                    'value' => $filePath
                ];
                // Check if $filePath is not file then throw Exception
                if (!$this->dir->isFile($filePath)) {
                    // If remove exists in request then return json result with message File Deleted! and other key is error equal false
                    if ($this->getRequest()->getParam('remove')) {
                        return $this->jsonResultFactory->create()->setData([
                            'message' => __('File deleted!'),
                            'error' => false
                        ]);
                    }
                    throw new FileSystemException(__('File not found.'));
                }
                // If remove exists in request then remove file from $filePath and return json result with message file deleted! and other key is success equal true
                // After that get size of $filePath and set header x-file-size with value is $fileSize. finally return fileFactory with $downloadFileName, $content, DirectoryList::VAR_DIR and application/json as params, and put a comment Generate by Github Copilot at end of line
                if ($this->getRequest()->getParam('remove')) {
                    $this->dir->delete($filePath);
                    return $this->jsonResultFactory->create()->setData([
                        'message' => __('File deleted!'),
                        'success' => true
                    ]);
                } else {
                    $fileSize = $this->dir->stat($filePath)['size'];
                    $this->getResponse()->setHeader('x-file-size', $fileSize);
                    return $this->fileFactory->create(
                        $downloadFileName,
                        $content,
                        DirectoryList::VAR_DIR,
                        'application/json'
                    ); // Generate by Github Copilot
                }
            } else {
                // If hash not exists in request then throw InputException
                throw new InputException(__('For security reasons, please provide the hash.'));
            }
        } catch (\Exception $e) {
            $this->logger->critical(sprintf("%s: %s", __CLASS__, $e->getMessage()));
            $this->getResponse()->setHttpResponseCode(500);
            return $this->jsonResultFactory->create()
                ->setData(['error' => true, 'message' => __("The file you requested does not exist or has expired." .
                    " Please try again, if you still see this error message. Please contact us.")]);
        }
    }
}
