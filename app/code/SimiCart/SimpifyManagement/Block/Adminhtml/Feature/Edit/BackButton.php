<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\Adminhtml\Feature\Edit;

use Magento\Framework\UrlInterface as IUrl;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    protected IUrl $urlBuilder;

    /**
     * Back button constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * Get back button data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get back url
     *
     * @return string
     */
    protected function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }

    /**
     * Get url
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function getUrl(string $route, array $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
