<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        dd($this->getRequest()->getPost());
    }
}
