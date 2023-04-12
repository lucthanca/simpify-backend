<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features\ConfigField;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use SimiCart\SimpifyManagement\Api\FeatureFieldRepositoryInterface as IFeatureFieldRepository;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SimiCart_SimpifyManagement::feature_field_delete';
    protected JsonFactory $resultJsonFactory;
    protected IFeatureFieldRepository $featureFieldRepository;

    /**
     * Delete constructor
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param IFeatureFieldRepository $featureFieldRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        IFeatureFieldRepository $featureFieldRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $jsonFactory;
        $this->featureFieldRepository = $featureFieldRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $error = false;

        $resultJson = $this->resultJsonFactory->create();
        try {
            $ffId = $this->getRequest()->getParam('id');
            $this->featureFieldRepository->deleteById((int) $ffId);
            $message = __('You deleted the Feature config field.');
        } catch (\Exception $e) {
            $message = __('Could not delete the field.');
            $error = true;
        }
        $resultJson->setData(
            [
                'message' => $message,
                'error' => $error,
            ]
        );

        return $resultJson;
    }
}
