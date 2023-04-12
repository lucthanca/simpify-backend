<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FieldInputType implements OptionSourceInterface
{
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_DROPDOWN = 'dropdown';
    const TYPE_TOGGLE = 'toggle';

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'label' => __('Field'),
                'value' => self::TYPE_TEXT,
            ],
            [
                'label' => __('Textarea'),
                'value' => self::TYPE_TEXTAREA,
            ],
            [
                'label' => __('Dropdown'),
                'value' => self::TYPE_DROPDOWN,
            ],
            [
                'label' => __('Toggle'),
                'value' => self::TYPE_TOGGLE,
            ]
        ];
    }
}
