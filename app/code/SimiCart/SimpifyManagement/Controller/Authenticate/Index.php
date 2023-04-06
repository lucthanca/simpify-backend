<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Authenticate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory as FJson;
use Magento\Framework\Controller\Result\RedirectFactory;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;
use SimiCart\SimpifyManagement\Model\InstallShop;

class Index implements ActionInterface
{
    private RequestInterface $request;
    private InstallShop $installShop;
    private FJson $jsonFactory;
    private RedirectFactory $redirectFactory;
    private ShopRepositoryInterface $shopRepository;

    /**
     * @param RequestInterface $request
     * @param InstallShop $installShop
     * @param FJson $jsonFactory
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        RequestInterface $request,
        InstallShop $installShop,
        FJson $jsonFactory,
        RedirectFactory $redirectFactory,
        ShopRepositoryInterface $shopRepository
    ) {
        $this->request = $request;
        $this->installShop = $installShop;
        $this->jsonFactory = $jsonFactory;
        $this->redirectFactory = $redirectFactory;
        $this->shopRepository = $shopRepository;
    }

    public function execute()
    {
//        vadu_html(['run auth', $this->getRequest()->getParams()]);


        // Determine if the HMAC is correct
        $shop = $this->shopRepository->getByDomain($this->getRequest()->getParam('shop'));
        if (!$shop->getId()) {
            throw new \Exception('Shop not existed. Validation failed.');
        }
        if (!$shop->getShopApi()->verifyRequest($this->getRequest())) {
            throw new \Exception('HMAC verification failed.');
        }

        $result = $this->installShop->execute($this->getRequest()->getParam('shop'), $this->getRequest()->getParam('code'));
//        return $this->redirectFactory->create()->setUrl($result['shop_url'] ?? "https://{$this->getRequest()->getParam('shop')}/admin/apps");
        if (isset($result['shop'])) {
            $data = [
                'shop_name' => $result['shop']->getShopName(),
            ];
        }
         else {
             $data = [
                 'error' => $result['errorMessage'] ?? ''
             ];
         }
        return $this->jsonFactory->create()->setData($data);
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
