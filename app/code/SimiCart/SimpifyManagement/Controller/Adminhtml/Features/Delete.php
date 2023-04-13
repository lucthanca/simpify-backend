<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use SimiCart\SimpifyManagement\Api\FeatureRepositoryInterface;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SimiCart_SimpifyManagement::feature_delete';
    protected FeatureRepositoryInterface $featureRepository;
    protected JsonFactory $jsonFactory;

    /**
     * @param Context $context
     * @param FeatureRepositoryInterface $featureRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        FeatureRepositoryInterface $featureRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->featureRepository = $featureRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $this->featureRepository->deleteById((int) $id);
            $this->messageManager->addSuccessMessage(__("You deleted the Feature."));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        if ($this->getRequest()->getParam('isAjax') === 'true') {
            return $this->jsonFactory->create()
                ->setData(['success' => true, 'message' => __("You deleted the Feature.")]);
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
