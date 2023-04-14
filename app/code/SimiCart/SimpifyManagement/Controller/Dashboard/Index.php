<?php

namespace SimiCart\SimpifyManagement\Controller\Dashboard;

use Assert\Assert;
use Assert\AssertionFailedException;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\Exception\NoSuchEntityException;
use SimiCart\SimpifyManagement\Model\View\SimpifyPageFactory as PageFactory;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface as IShopRepository;
use SimiCart\SimpifyManagement\Exceptions\SignatureVerificationException;
use SimiCart\SimpifyManagement\Helper\UtilTrait;
use SimiCart\SimpifyManagement\Model\ConfigProvider;
use SimiCart\SimpifyManagement\Registry\CurrentShop;

class Index implements HttpGetActionInterface
{
    use UtilTrait;

    private PageFactory $pageFactory;
    private IRequest $request;
    protected ConfigProvider $configProvider;
    protected CurrentShop $currentShop;
    protected IShopRepository $shopRepository;

    /**
     * Dashboard Index constructor
     *
     * @param PageFactory $pageFactory
     * @param IRequest $request
     * @param ConfigProvider $configProvider
     * @param CurrentShop $currentShop
     * @param IShopRepository $shopRepository
     */
    public function __construct(
        PageFactory $pageFactory,
        IRequest $request,
        ConfigProvider $configProvider,
        CurrentShop $currentShop,
        IShopRepository $shopRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
        $this->configProvider = $configProvider;
        $this->currentShop = $currentShop;
        $this->shopRepository = $shopRepository;
    }

    /**
     * Verify request and return page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $page = $this->getPageFactory()->create();
        try {
            $this->verifyRequest();

            $page->getLayout()->getUpdate()->addHandle('handler_simpify_dashboard');
            $shop = $this->shopRepository->getByDomain($this->getRequest()->getParam('shop'));
            if (!$shop->getId()) {
                throw new NoSuchEntityException(__("Shop not found on our system!"));
            }
            $this->currentShop->set($shop);
        } catch (\Exception|\Throwable $e) {
            $page->getLayout()->getUpdate()->addHandle('handler_simpify_404');
        }

        return $page;
    }

    /**
     * Verify request
     *
     * @return void
     * @throws SignatureVerificationException
     * @throws AssertionFailedException
     */
    protected function verifyRequest()
    {
        $params = $this->getRequest()->getParams();

        if (!isset($params['hmac'])) {
            throw new SignatureVerificationException(__('Unable to verify signature.'));
        }

        if (!isset($params['shop'])) {
            throw new SignatureVerificationException(__('Shop domain missing.'));
        }

        $requestHmac = $params['hmac'];
        $availableCheckParams = ['host', 'session'];
        $data = [$params['shop']];
        foreach ($availableCheckParams as $key) {
            if (isset($params[$key])) {
                $data[] = $params[$key];
            }
        }
        $verifyHmac = $this->createHmac(
            ["raw" => true, "data" => implode(".", $data)],
            $this->configProvider->getApiSecret()
        );
        Assert::that($requestHmac === $this->base64UrlEncode($verifyHmac))
            ->true(__('Signature not verified.')->render());
    }

    /**
     * Get page factory
     *
     * @return PageFactory
     */
    public function getPageFactory(): PageFactory
    {
        return $this->pageFactory;
    }

    /**
     * Get request object
     *
     * @return IRequest
     */
    public function getRequest(): IRequest
    {
        return $this->request;
    }
}
