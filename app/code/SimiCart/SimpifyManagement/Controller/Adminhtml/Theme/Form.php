<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Theme;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use SimiCart\SimpifyManagement\Model\ThemeFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\Theme as ThemeResource;

class Form extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    // define page factory, theme factory and theme resource model
    protected $pageFactory;
    protected $themeFactory;
    protected $themeResource;
    protected $registry;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param ThemeFactory $themeFactory
     * @param ThemeResource $themeResource
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ThemeFactory $themeFactory,
        ThemeResource $themeResource,
        // inject registry
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->themeFactory = $themeFactory;
        $this->themeResource = $themeResource;
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        // Create emtpty theme
        $theme = $this->themeFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            $this->themeResource->load($theme, $id);
        }
        $title = __('New Theme');
        if (isset($theme) && $theme->getId()) {
            $title = __("Edit: %1", $theme->getName());
        }
        $this->registry->register('current_theme', $theme);
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend($title);
        return $page;
    }
}
