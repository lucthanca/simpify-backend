<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use SimiCart\SimpifyManagement\Api\FeatureRepositoryInterface;

class Form extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    protected PageFactory $pageFactory;
    protected FeatureRepositoryInterface $featureRepository;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param FeatureRepositoryInterface $featureRepository
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        FeatureRepositoryInterface $featureRepository
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->featureRepository = $featureRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $feature = $this->featureRepository->getById((int) $id);
        }
        $title = __('New Feature');
        if (isset($feature) && $feature->getId()) {
            $title = __("Edit: %1", $feature->getName());
        }
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend($title);
        return $page;
    }
}
