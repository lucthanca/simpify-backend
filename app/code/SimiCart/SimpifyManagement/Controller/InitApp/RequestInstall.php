<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\InitApp;

use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;
use SimiCart\SimpifyManagement\Model\View\SimpifyPageFactory;
use SimiCart\SimpifyManagement\Registry\CurrentShop;

class RequestInstall implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    private SimpifyPageFactory $pageFactory;
    private ShopRepositoryInterface $shopRepository;
    private CurrentShop $currentShop;
    private RequestInterface $request;
    private \Psr\Log\LoggerInterface $logger;

    /**
     * @param SimpifyPageFactory $pageFactory
     * @param ShopRepositoryInterface $shopRepository
     * @param CurrentShop $currentShop
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        SimpifyPageFactory $pageFactory,
        ShopRepositoryInterface $shopRepository,
        CurrentShop $currentShop,
        RequestInterface $request,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->pageFactory = $pageFactory;
        $this->shopRepository = $shopRepository;
        $this->currentShop = $currentShop;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $shop = $this->shopRepository->getByDomain($this->request->getParam('shop'));
            $this->currentShop->set($shop);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw $e;
        }
        return $this->pageFactory->create();
    }
}
