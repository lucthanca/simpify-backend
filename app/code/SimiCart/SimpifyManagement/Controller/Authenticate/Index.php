<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Authenticate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory as FJson;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;
use SimiCart\SimpifyManagement\Model\AuthenticateShop;
use SimiCart\SimpifyManagement\Model\ConfigProvider;

class Index implements ActionInterface
{
    protected RequestInterface $request;
    protected FJson $jsonFactory;
    protected RedirectFactory $redirectFactory;
    protected ShopRepositoryInterface $shopRepository;
    protected AuthenticateShop $authenticateShop;
    protected \Psr\Log\LoggerInterface $logger;
    protected ConfigProvider $configProvider;

    /**
     * Authenticate shop constructor
     *
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param FJson $jsonFactory
     * @param RedirectFactory $redirectFactory
     * @param ShopRepositoryInterface $shopRepository
     * @param AuthenticateShop $authenticateShop
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        RequestInterface $request,
        FJson $jsonFactory,
        RedirectFactory $redirectFactory,
        ShopRepositoryInterface $shopRepository,
        AuthenticateShop $authenticateShop,
        ConfigProvider $configProvider
    ) {
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
        $this->redirectFactory = $redirectFactory;
        $this->shopRepository = $shopRepository;
        $this->authenticateShop = $authenticateShop;
        $this->logger = $logger;
        $this->configProvider = $configProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $shop = $this->shopRepository->getByDomain($this->getRequest()->getParam('shop'));
            $result = $this->authenticateShop->execute($shop, $this->getRequest()->getParam('code'));
            if ($result !== true) {
                throw new LocalizedException(__("Validation failed."));
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonFactory->create()->setData(['message' => $e->getMessage()]);
        }

        // Authentication done => redirect to main app
        $params = $this->getRequest()->getParams();
        if (isset($params['secure'])) {
            unset($params['secure']);
        }
        $redirectQuery = http_build_query($params);
        $frontendUrl = $this->configProvider->getFrontendUrl();
        $redirectUrl = "$frontendUrl/?$redirectQuery&force_to_shopify=1";

        return $this->redirectFactory->create()->setUrl($redirectUrl);
//        return $this->redirectFactory
//            ->create()
//            ->setPath(
//                'simpify/initapp',
//                ['shop' => $shop->getShopDomain(), 'host' => $this->getRequest()->getParam('host')]
//            );
    }

    /**
     * Get request object
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
