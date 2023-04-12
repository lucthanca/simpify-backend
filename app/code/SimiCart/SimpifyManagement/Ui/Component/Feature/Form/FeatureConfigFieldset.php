<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Feature\Form;

use Magento\Framework\View\Element\ComponentVisibilityInterface;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class FeatureConfigFieldset extends Fieldset implements ComponentVisibilityInterface
{
    /**
     * FeatureConfigFieldset constructor
     *
     * @param ContextInterface $context
     * @param array $components
     * @param array $data
     */
    public function __construct(ContextInterface $context, array $components = [], array $data = [])
    {
        parent::__construct($context, $components, $data);
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function isComponentVisible(): bool
    {
        return (bool) $this->context->getRequestParam('id');
    }
}
