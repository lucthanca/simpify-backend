<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\InitApp;

use SimiCart\SimpifyManagement\Model\View\SimpifyPageFactory;

class RequestInstall implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    private SimpifyPageFactory $pageFactory;

    /**
     * @param SimpifyPageFactory $pageFactory
     */
    public function __construct(
        SimpifyPageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
