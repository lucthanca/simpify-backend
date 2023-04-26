<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\AppText;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\InputException;
use SimiCart\SimpifyManagement\Model\DefaultLanguageFactory;
use SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected DataPersistorInterface $dataPersistor;
    private DefaultLanguageFactory $defaultLanguageFactory;
    private DefaultLanguage $defaultLanguageResource;

    /**
     * Save constructor
     *
     * @param Context $context
     * @param DefaultLanguageFactory $defaultLanguageFactory
     * @param DefaultLanguage $defaultLanguageResource
     */
    public function __construct(
        Context $context,
        DefaultLanguageFactory $defaultLanguageFactory,
        DefaultLanguage $defaultLanguageResource
    ) {
        parent::__construct($context);
        $this->defaultLanguageFactory = $defaultLanguageFactory;
        $this->defaultLanguageResource = $defaultLanguageResource;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $post = $this->getRequest()->getPost()->toArray();
        if (empty($post['text'])) {
            throw new InputException(__("Text is required!"));
        }
        $message = __("New text created.");
        $l = $this->defaultLanguageFactory->create();
        if (!empty($post['entity_id'])) {
            $this->defaultLanguageResource->load($l, $post['entity_id']);
            $message = __("You saved the text.");
        }
        $l->setText($post['text']);
        try {
            $this->defaultLanguageResource->save($l);
            $this->messageManager->addSuccessMessage($message);
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        } catch (\Exception $e) {
            $this->dataPersistor->set('apptext_', $post);
            $this->messageManager->addErrorMessage($e->getMessage());
            $returnParams = isset($post['entity_id']) ? ['id' => $post['entity_id']] : [];
            return $this->resultRedirectFactory->create()->setPath('*/*/edit', $returnParams);
        }
    }

    /**
     * Rewrite check is allow
     *
     * @return true
     */
    protected function _isAllowed()
    {
        return true;
    }
}
