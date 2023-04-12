<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use SimiCart\SimpifyManagement\Api\FeatureRepositoryInterface as IFeatureRepository;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected IFeatureRepository $featureRepository;
    protected DataPersistorInterface $dataPersistor;

    /**
     * Controller Save Constructor
     *
     * @param Context $context
     * @param IFeatureRepository $featureRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        IFeatureRepository $featureRepository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->featureRepository = $featureRepository;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $post = $this->getRequest()->getPost()->toArray();

        if (!isset($post['feature']) && !isset($post['feature']['name'])) {
            throw new InputException(__("Feature name is required!"));
        }

        $returnParams = isset($post['feature']['entity_id']) ? ['id' => $post['feature']['entity_id']] : [];

        try {
            $message = __("The feature created.");
            if (isset($post['feature']['entity_id'])) {
                try {
                    $feature = $this->featureRepository->getById((int) $post['feature']['entity_id']);
                    if (!$feature->getId()) {
                        throw new CouldNotSaveException(__("Feature not found!"));
                    }
                    $message = __("You saved the Feature.");
                } catch (\Exception $e) {
                    $feature = $this->featureRepository->getNewEmptyItem();
                }
            } else {
                $feature = $this->featureRepository->getNewEmptyItem();
            }

            $feature->setData($post['feature']);
            $this->featureRepository->save($feature);
            $this->messageManager->addSuccessMessage($message);
            if ($this->getRequest()->getParam('back', false)) {
                return $this->resultRedirectFactory->create()->setPath('*/*/edit', $returnParams);
            }
        } catch (CouldNotSaveException|NoSuchEntityException $e) {
            $this->dataPersistor->set('feature_', $post);
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath('*/*/edit', $returnParams);
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
