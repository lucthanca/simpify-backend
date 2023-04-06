<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\InitApp;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface as IShopRepository;
use SimiCart\SimpifyManagement\Registry\CurrentShop as RCurrentShop;
use \Psr\Log\LoggerInterface as ILogger;
use Magento\Framework\View\Element\Template;

class Index implements HttpGetActionInterface
{
    private RequestInterface $request;
    private UrlInterface $url;
    private PageFactory $pageFactory;
    private RCurrentShop $currentShopRegistry;
    private IShopRepository $shopRepository;
    private ILogger $logger;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param UrlInterface $url
     * @param PageFactory $pageFactory
     * @param RCurrentShop $currentShopReg
     * @param IShopRepository $shopRepository
     * @param ILogger $logger
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $url,
        PageFactory $pageFactory,
        RCurrentShop $currentShopReg,
        IShopRepository $shopRepository,
        ILogger $logger
    ) {
        $this->request = $request;
        $this->url = $url;
        $this->pageFactory = $pageFactory;
        $this->currentShopRegistry = $currentShopReg;
        $this->shopRepository = $shopRepository;
        $this->logger = $logger;
    }

    /**
     * Init shop and return to full page redirect or dashboard
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
//        vadu_html([
//            "INIT INSTALL APP" => $this->getRequest()->getParams()
//        ]);
        try {
            $this->initShop();
        } catch (\Exception $e) {
            $this->logger->critical('INIT SHOP FAILED: ' . $e);
            return $this->getPageFactory()->create(false, [
                'template' => 'SimiCart_SimpifyManagement::initApp/404.phtml',
            ]);
        }

        $page = $this->getPageFactory()->create(false, [
            'template' => 'SimiCart_SimpifyManagement::initApp/fullpageRedirect.phtml',
        ]);
        $unnecessaryHeadBlocks = $page->getLayout()->getChildBlocks('head.additional');
        $unnecessaryHeadBlocksFiltered = array_filter($unnecessaryHeadBlocks, function ($block) {
            return $block->getNameInLayout() !== 'head_fullpage_redirect_script' ;
        });
        /* @var Template $block */
        foreach ($unnecessaryHeadBlocksFiltered as $block) {
            $page->getLayout()->unsetChild('head.additional', $block->getNameInLayout());
        }
        // Remove require js block
        $page->getLayout()->unsetElement('require.js');
        return $page;
    }

    /**
     * Get shop or create new shop instance by provided request param
     *
     * @return void
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initShop()
    {
        if (!$this->getRequest()->getParam('shop')) {
            throw new LocalizedException(__('Missing shop parameter.'));
        }

        $shopDomain = $this->getRequest()->getParam('shop');
        $shop = $this->shopRepository->getByDomain($shopDomain);
        if (!$shop->getId()) {
            $shopData = [
                IShop::SHOP_NAME => $shopDomain,
                IShop::SHOP_DOMAIN => $shopDomain,
                IShop::SHOP_EMAIL => "shop@$shopDomain",
            ];

            $this->shopRepository->createShop($shopData);
            $shop = $this->shopRepository->getByDomain($shopDomain);
        }
        $this->currentShopRegistry->set($shop);
    }

    /**
     * Get request
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Get url builder
     *
     * @return UrlInterface
     */
    public function getUrlBuilder(): UrlInterface
    {
        return $this->url;
    }

    /**
     * Get page result factory
     *
     * @return PageFactory
     */
    public function getPageFactory(): PageFactory
    {
        return $this->pageFactory;
    }
}
