<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Form\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ThemeAddNew implements ButtonProviderInterface
{
    // Register urlBuilder variable, authorization variable and constructor function, assign urlBuilder and authorization to $context->getUrlBuilder() and $this->authorization
    protected \Magento\Framework\UrlInterface $urlBuilder;
    protected \Magento\Framework\AuthorizationInterface $authorization;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\AuthorizationInterface $authorization
    ) {
        // Assign urlBuilder and authorization to $context->getUrlBuilder() and $this->authorization
        $this->urlBuilder = $context->getUrlBuilder();
        $this->authorization = $authorization;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        // Check if user is allowed to add new theme, if not, return empty array
        if (!$this->authorization->isAllowed('SimiCart_SimpifyManagement::theme_new')) {
            return [];
        }
        // Return array with label, on_click, class and sort_order
        return [
            'label' => __('Add New Theme'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*/new')),
            'class' => 'primary',
            'sort_order' => 10
        ];
    }

    // Define getUrl function

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        // Return url with route and params
        return $this->urlBuilder->getUrl($route, $params);
    }
}
