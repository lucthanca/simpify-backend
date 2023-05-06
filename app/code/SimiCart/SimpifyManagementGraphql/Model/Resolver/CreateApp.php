<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\Data\AppInterface;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;
use SimiCart\SimpifyManagement\Model\ThemeFactory;
use SimiCart\SimpifyManagement\Model\AppFactory;
use SimiCart\SimpifyManagementGraphql\Exceptions\GraphQlUncommonErrorException;
use SimiCart\SimpifyManagementGraphql\Model\Formatter\AppFormatterTrait;

class CreateApp implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    use AppFormatterTrait;

    protected $fileFields = [
        'app_logo',
        'app_icon',
        'splash_image',
    ];
    private AppFactory $appFactory;
    private \Psr\Log\LoggerInterface $logger;
    private StoreManagerInterface $storeManager;
    private ImageUploader $imageUploader;

    private $context;
    private ShopRepositoryInterface $shopRepository;
    private ThemeFactory $themeFactory;

    /**
     * CreateApp constructor.
     *
     * @param AppFactory $appFactory
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param ImageUploader $imageUploader
     * @param ShopRepositoryInterface $shopRepository
     * @param ThemeFactory $themeFactory
     */
    public function __construct(
        AppFactory $appFactory,
        \Psr\Log\LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        ImageUploader $imageUploader,
        ShopRepositoryInterface $shopRepository,
        ThemeFactory $themeFactory
    ) {
        $this->appFactory = $appFactory;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->imageUploader = $imageUploader;
        $this->shopRepository = $shopRepository;
        $this->themeFactory = $themeFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->context = $context;
        $isSimpiShop = $context->getExtensionAttributes()->getIsSimpifyShop();
        if ($isSimpiShop) {
            $shopId = $context->getExtensionAttributes()->getSimpifyShopId();
            $this->validate($args);
            try {
                $shop = $this->shopRepository->getById($shopId);
                if (!$shop->getId()) {
                    throw new GraphQlAuthorizationException(__("Unauthorized Shop!"));
                }
                $input = $args['input'];
                /** @var AppInterface $app */
                $app = $this->appFactory->create();
                // check exists app id then load app
                if (isset($input['app_id'])) {
                    $decodedAppId = base64_decode($input['app_id']);
                    if ($decodedAppId) {
                        $app->load($decodedAppId);
                    }
                }
                $moreInfo = $shop->getArrayMoreInfo();
                $app->setIndustry($moreInfo['industry'] ?? 'default');
                $app->setShopId((int) $shopId);

                if (array_key_exists('app_name', $input)) {
                    $app->setAppName($input['app_name']);
                }

                if (array_key_exists('industry', $input)) {
                    $app->setIndustry($input['industry']);
                }

                if (array_key_exists('splash_is_full', $input)) {
                    $app->setSplashIsFull($input['splash_is_full']);
                }

                if (array_key_exists('splash_bg_color', $input)) {
                    $app->setSplashBgColor($input['splash_bg_color']);
                }

                foreach ($this->fileFields as $field) {
                    if (array_key_exists($field, $input)) {
                        if ($input[$field] === null) {
                            // Handle delete existed file

                            $app->setData($field, null);
                            continue;
                        }
                        $url = $this->processAppImage($input[$field]['file']);
                        $app->setData($field, $url);
                    }
                }
                try {
                    $app->save();
                } catch (\Exception $e) {
                    throw new CouldNotSaveException(__("Failed to save App."));
                }
            } catch (CouldNotSaveException $e) {
                throw new GraphQlUncommonErrorException(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                throw new GraphQlUncommonErrorException(__("Failed to save App."));
            }

            try {
                $this->processAppLayout($app, $input);
            } catch (GraphQlInputException $e) {
                throw $e;
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                throw new GraphQlUncommonErrorException(__("Failed to save Theme Settings."));
            }
            return $this->formatAppOutput($app);
        }
        throw new GraphQlAuthorizationException(__("Unauthorized Shop!"));
    }

    protected function processAppLayout(AppInterface $app, array $payload)
    {
        if (empty($payload['layout'])) {
            return;
        }
        $appLayout = $app->getAppLayout();
        $layoutPayload = $payload['layout'];

        // validate if appLayout has no Id, then theme_uid is required. if theme_uid not defined, rollback transation of app
        if (!$appLayout->getId()) {
            if (!array_key_exists('theme_uid', $layoutPayload)) {
                throw new GraphQlInputException(__("Theme Id is required."));
            }
        }

        if (array_key_exists('theme_uid', $layoutPayload)) {
            $decodedThemeId = base64_decode($layoutPayload['theme_uid']);
            $theme = $this->loadTheme((int) $decodedThemeId);
            $appLayout->setThemeId((int) $decodedThemeId);
        } else {
            $theme = $this->loadTheme((int) $appLayout->getThemeId());
        }

        if (!$appLayout->getId()) {
            $appLayout->setAppId((int) $app->getId());
        }
        if (array_key_exists('font', $layoutPayload)) {
            $appLayout->setFont($layoutPayload['font']);
        }
        if (array_key_exists('colors', $layoutPayload) && !empty($layoutPayload['colors'])) {
            $predefinedColors = $theme->getPairedColors();
            $requestedColors = $layoutPayload['colors'];
            if (!empty($predefinedColors)) {
                $expectedArray = array_column($predefinedColors, "key");
                $currentColors = $appLayout->getDecodedColors();

                // loop through $requestedColors and check if key is in $expectedArray then push to $currentColors
                foreach ($requestedColors as $requestedColor) {
                    if (in_array($requestedColor['key'], $expectedArray)) {
                        $requestedColor['label'] = $predefinedColors[$requestedColor['key']]['label'];
                        $currentColors[] = $requestedColor;
                    }
                }
                $appLayout->setColors($currentColors);
            }
        }
        if (array_key_exists('menu', $layoutPayload)) {
            $appLayout->setMenu($layoutPayload['menu']);
        }
        if (array_key_exists('homepage', $layoutPayload)) {
            $appLayout->setHomepage($layoutPayload['homepage']);
        }
        if (array_key_exists('collection_page', $layoutPayload)) {
            $appLayout->setCollectionPage($layoutPayload['collection_page']);
        }

        if (array_key_exists('product_page', $layoutPayload)) {
            $appLayout->setProductPage($layoutPayload['product_page']);
        }

        if (array_key_exists('landing_page', $layoutPayload)) {
            $appLayout->setLandingPage($layoutPayload['landing_page']);
        }
        $appLayout->save();
    }

    protected function loadTheme(int $themeId): ThemeInterface
    {
        $theme = $this->themeFactory->create();
        $theme->getResource()->load($theme, $themeId);
        if (!$theme->getId()) {
            throw new GraphQlInputException(__("Theme not found!"));
        }
        return $theme;
    }

    protected function validate(?array $args)
    {
        if (!isset($args['input'])) {
            throw new GraphQlInputException(__("Invalid input!"));
        }
        $input = $args['input'];
        if (!isset($input['app_id'])) {
            if (!isset($input['app_name'])) {
                throw new GraphQlInputException(__("Missing App Name!"));
            }
        }

        // loop $fileFieldValidation Validate if input not contains file field, then skip. else validate name type and file keys required when delete key not exists
        foreach ($this->fileFields as $field) {
            if (!array_key_exists($field, $input)) {
                continue;
            }
            // Just remove current file, skip it
            if ($input[$field] === null) {
                continue;
            }
            $file = $input[$field];
            if (!isset($file['name'])) {
                throw new GraphQlInputException(__("Missing file name!"));
            }
            if (!isset($file['type'])) {
                throw new GraphQlInputException(__("Missing file type!"));
            }
            if (!isset($file['file'])) {
                throw new GraphQlInputException(__("Missing file content!"));
            }
        }
    }

    /**
     * Process move file from tmp to media simpify app folder
     *
     * @param string $imageFile
     * @return string|null
     */
    protected function processAppImage($imageFile)
    {
        try {
            $store = $this->storeManager->getStore();
            $baseMediaDir = $store->getBaseMediaDir();
            if (method_exists($this->imageUploader, 'setAdditionalPath')) {
                $shopId = $this->context->getExtensionAttributes()->getSimpifyShopId();
                $this->imageUploader->setAdditionalPath($shopId);
            }
            $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageFile, true);
            return '/' . $baseMediaDir . '/' . $newImgRelativePath;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return null;
    }
}
