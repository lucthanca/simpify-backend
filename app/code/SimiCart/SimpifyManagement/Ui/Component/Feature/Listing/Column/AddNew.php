<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Feature\Listing\Column;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\AuthorizationInterface;

class AddNew implements ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * AddButton constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        AuthorizationInterface $authorization
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->authorization = $authorization;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        if (!$this->authorization->isAllowed('SimiCart_SimpifyManagement::feature_new')) {
            return [];
        }
        return [
            'label' => __('Add New Feature'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*/new')),
            'class' => 'primary',
            'sort_order' => 10
        ];
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
