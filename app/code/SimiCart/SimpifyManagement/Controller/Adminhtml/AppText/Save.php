<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\AppText;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
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

        try {
            if (is_array($post['text'])) {
                $message = $this->insertBulkText($post['text']);
            } else {
                $message = $this->saveSingleText($post);
            }
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
     * Insert bulk text
     *
     * @param array $text
     * @return \Magento\Framework\Phrase
     * @throws LocalizedException
     */
    protected function insertBulkText(array $text): \Magento\Framework\Phrase
    {
        $textData = array_map(function ($textItem) {
            return [
                'text' => $textItem['text'],
            ];
        }, $text);
        $this->defaultLanguageResource->insertBulkText($textData);
        return __('You save bulk of text.');
    }

    /**
     * Save single text
     *
     * @param array $post
     * @return \Magento\Framework\Phrase
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    protected function saveSingleText(array $post): \Magento\Framework\Phrase
    {
        $l = $this->defaultLanguageFactory->create();
        $message = __('New Text created.');
        if (!empty($post['entity_id'])) {
            $this->defaultLanguageResource->load($l, $post['entity_id']);
            $message = __("You saved the text.");
        }
        $l->setText($post['text']);
        $this->defaultLanguageResource->save($l);
        return $message;
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
