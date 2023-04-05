<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Authenticate;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory as FJson;
use SimiCart\SimpifyManagement\Model\InstallShop;

class Index implements ActionInterface
{
    private RequestInterface $request;
    private InstallShop $installShop;
    private FJson $jsonFactory;

    /**
     * @param RequestInterface $request
     * @param InstallShop $installShop
     * @param FJson $jsonFactory
     */
    public function __construct(
        RequestInterface $request,
        InstallShop $installShop,
        FJson $jsonFactory
    ) {
        $this->request = $request;
        $this->installShop = $installShop;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $result = $this->installShop->execute($this->getRequest()->getParam('shop'), $this->getRequest()->getParam('code'));
        return $this->jsonFactory->create()->setData($result);
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
