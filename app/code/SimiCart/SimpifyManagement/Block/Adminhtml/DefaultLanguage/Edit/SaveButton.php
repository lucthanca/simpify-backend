<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\Adminhtml\DefaultLanguage\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    protected AuthorizationInterface $authorization;
    protected IRequest $request;

    /**
     * Save Button constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param AuthorizationInterface $authorization
     * @param IRequest $request
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        AuthorizationInterface $authorization,
        IRequest $request
    ) {
        parent::__construct($context, $registry);
        $this->authorization = $authorization;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        if ($this->request->getParam('id') &&
            !$this->authorization->isAllowed('SimiCart_SimpifyManagement::apptext_update')
        ) {
            return [];
        }
        return [
            'label' => __('Save Text'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
