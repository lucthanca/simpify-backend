<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Model\AppLanguageTextFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\AppLanguageText;
use SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;

class GenerateLanguageTextExample extends GetAppTexts implements ResolverInterface
{
    const FILE_EXT = ".json";
    const MAIN_VAR_DIR = 'simpify_app_language';
    const FILE_NAME = 'example';

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    protected $outPutDirectory;

    protected LoggerInterface $logger;
    protected UrlInterface $url;
    protected AppLanguageText $appLanguageTextResource;
    protected \SimiCart\SimpifyManagement\Model\AppLanguageTextFactory $appLanguageTextFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     * @param UrlInterface $url
     * @param AppLanguageText $appLanguageTextResource
     * @param AppLanguageTextFactory $appLanguageTextFactory
     * @throws FileSystemException
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger,
        UrlInterface $url,
        AppLanguageText $appLanguageTextResource,
        \SimiCart\SimpifyManagement\Model\AppLanguageTextFactory $appLanguageTextFactory
    ) {
        parent::__construct($collectionFactory);
        $this->outPutDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->logger = $logger;
        $this->url = $url;
        $this->appLanguageTextResource = $appLanguageTextResource;
        $this->appLanguageTextFactory = $appLanguageTextFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['code'])) {
            throw new GraphQlInputException(__('Please provided a code.'));
        }
        if (empty($args['app_id'])) {
            throw new GraphQlInputException(__('Please provided APP ID.'));
        }

        try {
            $fileName = self::FILE_NAME . self::FILE_EXT;
            $emptyExampleFilePath = self::MAIN_VAR_DIR . DIRECTORY_SEPARATOR . $fileName;
            $serializer = new \Magento\Framework\Serialize\Serializer\Json();

            // Get app language text by code and app id
            $appLanguageText = $this->appLanguageTextFactory->create();
            $this->appLanguageTextResource->loadAppText(
                $appLanguageText,
                (int) $args['app_id'],
                $args['code']
            );
            // Check if has id then getTextData and unserialize using json_decode
            $appConfiguredText = [];
            if ($appLanguageText->getId()) {
                $textData = $appLanguageText->getTextData();
                $appConfiguredText = $serializer->unserialize($textData);
            }

            if (!$this->outPutDirectory->isFile($emptyExampleFilePath)) {
                $this->generateTextExampleFile();
            }

            // Get file content
            $fileContent = $this->outPutDirectory->readFile($emptyExampleFilePath);
            // unserialize file content using \Magento\Framework\Serialize\Serializer\Json
            $fileContent = $serializer->unserialize($fileContent);
            // So sánh 2  array $fileContent và $appConfiguredText, đẩy vào $appConfiguredText những phần tử mà $fileContent có mà $appConfiguredText không có
            $result = array_merge($fileContent, $appConfiguredText);

            // generate new file name using hash md5
            $nameHash = md5($args['code'] . $args['app_id']);
            $newFileName = $nameHash . self::FILE_EXT;
            $newDestPath = self::MAIN_VAR_DIR . DIRECTORY_SEPARATOR . $newFileName;
            // Save to var dir
            $this->outPutDirectory->writeFile($newDestPath, $serializer->serialize($result));
            // generate download link using url object with param hash is $nameHash, _secure is true and routePath is simpify/apptext/example
            $downloadLink = $this->url->getUrl(
                'simpify/apptext/example',
                [
                    'hash' => $nameHash,
                    '_secure' => true,
                ]
            );

            return [
                'download_link' => $downloadLink,
            ];
        } catch (\Exception $e) {
            $this->logger->error($e);
            throw new GraphQlInputException(__(
                "Something went wrong when we are trying" .
                "to generate text data example file." .
                " Please try again or contact us."
            ));
        }
    }

    /**
     * Generate language text link
     *
     * @return void
     * @throws FileSystemException
     */
    protected function generateTextExampleFile()
    {
        $allText = $this->getAllTexts();
        $result = [];
        foreach ($allText as $text) {
            $result[$text] = "";
        }

        $fileName = self::FILE_NAME . self::FILE_EXT;
        $destPath = self::MAIN_VAR_DIR . DIRECTORY_SEPARATOR . $fileName;

        $serializer = new \Magento\Framework\Serialize\Serializer\Json();
        $fileContent = $serializer->serialize($result);
        // Save to var dir
        $this->outPutDirectory->writeFile($destPath, $fileContent);
    }
}
