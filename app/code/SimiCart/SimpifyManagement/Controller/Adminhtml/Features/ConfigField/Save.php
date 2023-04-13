<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features\ConfigField;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\FeatureFieldOptionRepositoryInterface as IFeatureFieldOptionRepository;
use SimiCart\SimpifyManagement\Api\FeatureFieldRepositoryInterface as IFieldRepository;

class Save extends Action implements HttpPostActionInterface
{
    protected LoggerInterface $logger;
    protected IFieldRepository $fieldRepository;
    protected JsonFactory $jsonFactory;
    protected IFeatureFieldOptionRepository $featureFieldOptionRepository;
    private ResourceConnection $resourceConnection;
    private \Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface $transactionManager;

    /**
     * COnstreuctor
     *
     * @param Context $context
     * @param LoggerInterface $logger
     * @param IFieldRepository $fieldRepository
     * @param JsonFactory $jsonFactory
     * @param IFeatureFieldOptionRepository $featureFieldOptionRepository
     * @param ResourceConnection $resourceConnection
     * @param TransactionManagerInterface $transactionManager
     */
    public function __construct(
        Context $context,
        \Psr\Log\LoggerInterface $logger,
        IFieldRepository $fieldRepository,
        JsonFactory $jsonFactory,
        IFeatureFieldOptionRepository $featureFieldOptionRepository,
        ResourceConnection $resourceConnection,
        \Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface $transactionManager
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->fieldRepository = $fieldRepository;
        $this->jsonFactory = $jsonFactory;
        $this->featureFieldOptionRepository = $featureFieldOptionRepository;
        $this->resourceConnection = $resourceConnection;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $post = $this->getRequest()->getPost()->toArray();
        try {
            $this->transactionManager->start($this->resourceConnection->getConnection());
            $this->validate($post);
            $ff = $this->fieldRepository->getById((int) $post['entity_id']);
            if (!$ff->getId()) {
                $message = __("You add new Feature Config.");
            } else {
                $message = __("You update the Feature Config.");
            }

            if (empty($post['entity_id'])) {
                $post['entity_id'] = null;
            }

            if (isset($post['form_key'])) {
                unset($post['form_key']);
            }
            $ff->setData($post);
            $this->fieldRepository->save($ff);

            $this->processSaveFieldOptions($post, (int) $ff->getId());

            $result = [
                'success' => true,
                'message' => $message
            ];
        } catch (InputException $e) {
            $this->transactionManager->rollBack();
            $result = [
                'error' => true,
                'success' => false,
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            $this->logger->critical($e);
            $result = [
                'error' => true,
                'success' => false,
                'message' => __("Can not save the Feature Config")
            ];
        }
        return $this->jsonFactory->create()->setData($result);
    }

    /**
     * Process saving option field
     *
     * @param array $data
     * @param int $fieldId
     * @return void
     * @throws InputException
     */
    public function processSaveFieldOptions(array $data, int $fieldId)
    {
        if (!isset($data['options'])) {
            return;
        }
        $this->validateOptions($data['options']);
        $savedIds = [];
        foreach ($data['options'] as $option) {
            $fo = $this->featureFieldOptionRepository->getByFieldValue($fieldId, $option['value']);
            $fo->setValue($option['value']);
            $fo->setLabel($option['label']);
            $fo->setIsDefault($option['is_default'] === 'true' ? 1 : $option['is_default']);
            $fo->setFieldId($fieldId);
            $this->featureFieldOptionRepository->save($fo);
            $savedIds[] = $fo->getId();
        }

        $this->featureFieldOptionRepository->quickDeleteByFieldAndIds($fieldId, $savedIds);
    }

    /**
     * Validate options
     *
     * @param array $data
     * @return void
     * @throws InputException
     */
    protected function validateOptions(array $data): void
    {
        $firstValue = null;
        foreach ($data as $item) {
            if (empty($item['value'])) {
                throw new InputException(__("Value is required"));
            }
            if (empty($item['label'])) {
                throw new InputException(__("Label is required"));
            }
            if (!$firstValue) {
                $firstValue = $item['value'];
                continue;
            }
            if ($firstValue === $item['value']) {
                throw new InputException(__('Value must be unique.'));
            }
        }
    }

    /**
     * Validate post data
     *
     * @param array $payload
     * @return void
     * @throws InputException
     */
    protected function validate(array $payload): void
    {
        if (!isset($payload['name'])) {
            throw new InputException(__("Config Field Name is required!"));
        }
    }

    /**
     * Check can add new or update
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        $hasId = $this->getRequest()->getPost('entity_id');
        if ($hasId && !$this->_authorization->isAllowed('SimiCart_SimpifyManagement::feature_field_update')) {
            return false;
        }
        if (!$hasId && !$this->_authorization->isAllowed('SimiCart_SimpifyManagement::feature_field_new')) {
            return false;
        }

        return true;
    }
}
