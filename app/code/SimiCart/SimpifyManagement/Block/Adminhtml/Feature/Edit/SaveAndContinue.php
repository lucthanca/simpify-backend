<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\Adminhtml\Feature\Edit;

use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinue implements ButtonProviderInterface
{
    protected IRequest $request;
    protected AuthorizationInterface $authorization;

    /**
     * Constructor
     *
     * @param AuthorizationInterface $authorization
     * @param IRequest $request
     */
    public function __construct(
        AuthorizationInterface $authorization,
        IRequest $request
    ) {
        $this->authorization = $authorization;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $hasId = (bool) $this->request->getParam('id');
        if ($hasId && !$this->authorization->isAllowed('SimiCart_SimpifyManagement::feature_update')) {
            return [];
        }
        if (!$hasId && !$this->authorization->isAllowed('SimiCart_SimpifyManagement::feature_new')) {
            return [];
        }
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 60,
        ];
    }
}
