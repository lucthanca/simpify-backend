<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Authenticate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory as FJson;
use Magento\Framework\Controller\Result\RedirectFactory;
use SimiCart\SimpifyManagement\Model\InstallShop;

class Index implements ActionInterface
{
    private RequestInterface $request;
    private InstallShop $installShop;
    private FJson $jsonFactory;
    private RedirectFactory $redirectFactory;

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
        RedirectFactory $redirectFactory
    ) {
        $this->request = $request;
        $this->installShop = $installShop;
        $this->jsonFactory = $jsonFactory;
        $this->redirectFactory = $redirectFactory;
    }

    public function execute()
    {
        vadu_html(['run auth', $this->getRequest()->getParams()]);
        $result = $this->installShop->execute($this->getRequest()->getParam('shop'), $this->getRequest()->getParam('code'));
//        return $this->redirectFactory->create()->setUrl($result['shop_url'] ?? "https://{$this->getRequest()->getParam('shop')}/admin/apps");
        if (isset($result['shop'])) {
            $data = [
                'shop_name' => $result['shop']->getShopName(),
            ];
        }
         else {
             $data = [
                 'error' => $result['errorMessage'] ?? 'Server Error'
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
