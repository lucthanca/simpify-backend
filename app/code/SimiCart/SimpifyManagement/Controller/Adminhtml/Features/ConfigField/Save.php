<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Features\ConfigField;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\InputException;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\FeatureFieldRepositoryInterface as IFieldRepository;

class Save extends Action implements HttpPostActionInterface
{
    protected LoggerInterface $logger;
    protected IFieldRepository $fieldRepository;
    protected JsonFactory $jsonFactory;

    /**
     * COnstreuctor
     *
     * @param Context $context
     * @param LoggerInterface $logger
     * @param IFieldRepository $fieldRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        \Psr\Log\LoggerInterface $logger,
        IFieldRepository $fieldRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->fieldRepository = $fieldRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $post = $this->getRequest()->getPost()->toArray();
        try {
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
            $result = [
                'success' => true,
                'message' => $message
            ];
        } catch (InputException $e) {
            $result = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result = [
                'success' => false,
                'message' => __("Can not save the Feature Config")
            ];
        }
        return $this->jsonFactory->create()->setData($result);
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
