<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Authenticate;

use Assert\Assert;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;
use SimiCart\SimpifyManagement\Exceptions\SignatureVerificationException;
use SimiCart\SimpifyManagement\Helper\UtilTrait;
use SimiCart\SimpifyManagement\Model\ConfigProvider;
use Magento\Framework\Url;

class AdminLogin extends Action implements HttpGetActionInterface
{
    use UtilTrait;

    private ConfigProvider $configProvider;
    private RedirectFactory $redirectFactory;
    private ShopRepositoryInterface $shopRepository;
    private Url $urlBuilder;
    private StoreManagerInterface $storeManager;

    /**
     * AdminLogin constructor
     *
     * @param Context $context
     * @param ConfigProvider $configProvider
     * @param RedirectFactory $redirectFactory
     * @param ShopRepositoryInterface $shopRepository
     * @param Url $urlBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        ConfigProvider $configProvider,
        RedirectFactory $redirectFactory,
        ShopRepositoryInterface $shopRepository,
        Url $urlBuilder,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->configProvider = $configProvider;
        $this->redirectFactory = $redirectFactory;
        $this->shopRepository = $shopRepository;
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $hmac = $this->getRequest()->getParam('hmac');
            if (!$this->getRequest()->getParam('id')) {
                throw new SignatureVerificationException(__('Missing ID.'));
            }
            if (!$hmac) {
                throw new SignatureVerificationException(__('Missing HMAC signature.'));
            }

            $id = $this->getRequest()->getParam('id');
            // Verify hmac
            $buildHmac = $this->createHmac(
                ["data" => ["id" => $id], 'buildQuery' => true],
                $this->configProvider->getApiSecret()
            );
            Assert::that($buildHmac === $hmac)->true(__("Signature not verified.")->render());

            $shop = $this->shopRepository->getById((int) $id);
            if (!$shop->getId()) {
                throw new NoSuchEntityException(__("Shop not found! Please go back!"));
            }

            $params = ['shop' => $shop->getShopDomain()];
            $buildHmac = $this->createHmac(
                ["data" => implode(".", [$shop->getShopDomain()]), 'raw' => true],
                $this->configProvider->getApiSecret()
            );
            $params['hmac'] = $this->base64UrlEncode($buildHmac);

            $targetStore = $this->storeManager->getStore((int) $this->storeManager->getDefaultStoreView()->getId());
            $redirectUrl = $this->urlBuilder
                ->setScope($targetStore)
                ->getUrl('dashboard', ['_query' => $params, '_nosid' => true]);

            return $this->redirectFactory->create()->setUrl($redirectUrl);
        } catch (\Exception $e) {
            // @codingStandardsIgnoreStart
            print_r($e->getMessage());
            exit;
            // @codingStandardsIgnoreEnd
        }
    }
}
