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

        try {
            $feature = $this->featureRepository->getById((int) $post['feature']['entity_id']);
            if (!$feature->getId()) {
                throw new CouldNotSaveException(__("Feature not found!"));
            }

            $feature->setData($post['feature']);
            $this->featureRepository->save($feature);
            $this->messageManager->addSuccessMessage(__('You saved the feature.'));
        } catch (CouldNotSaveException|NoSuchEntityException $e) {
            $this->dataPersistor->set('feature_', $post);
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['id' => $post['feature']['entity_id']]);
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
