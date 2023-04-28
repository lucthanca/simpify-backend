<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\AppText;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage;

class Form extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    private PageFactory $pageFactory;
    private DefaultLanguage $defaultLanguageResource;
    private \SimiCart\SimpifyManagement\Model\DefaultLanguageFactory $defaultLanguageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        DefaultLanguage $defaultLanguageResource,
        \SimiCart\SimpifyManagement\Model\DefaultLanguageFactory $defaultLanguageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->defaultLanguageResource = $defaultLanguageResource;
        $this->defaultLanguageFactory = $defaultLanguageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $l = $this->defaultLanguageFactory->create();
            $this->defaultLanguageResource->load($l, $id);
        }
        $title = __('New Text');
        if (isset($l) && $l->getId()) {
            $title = __("Edit: %1", $l->getText());
        }
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend($title);
        return $page;
    }
}
