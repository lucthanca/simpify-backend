<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Theme;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

class NewAction extends Action implements HttpGetActionInterface
{
    // Define forward result factory
    protected $resultForwardFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
            $this->resultForwardFactory = $resultForwardFactory;
            parent::__construct($context);
    }

    /**
     * New theme form
     */
    public function execute()
    {
        // create forward to form
        $resultForward = $this->resultForwardFactory->create();
        // set params
        $resultForward->forward('form');
        return $resultForward;
    }
}
