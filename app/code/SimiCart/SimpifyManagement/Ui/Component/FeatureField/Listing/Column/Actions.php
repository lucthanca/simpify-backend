<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\FeatureField\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends Column
{
    const FEATURE_FIELD_PATH_DELETE = 'simpify/features_configfield/delete';

    protected UrlInterface $urlBuilder;

    /**
     * Actions constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare actions data
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }
        $items = &$dataSource['data']['items'];

        foreach ($items as &$item) {
            $name = $this->getData('name');
            if (isset($item['entity_id'])) {
                $item[$name]['edit'] = [
                    'callback' => [
                        [
                            'provider' => 'feature_form.areas.fields.'
                                . 'fields.'
                                . 'simpify_management_feature_fields_update_modal.'
                                . 'update_simpify_management_feature_fields_form_loader',
                            'target' => 'destroyInserted',
                        ],
                        [
                            'provider' => 'feature_form.areas.fields.'
                                . 'fields.'
                                . 'simpify_management_feature_fields_update_modal',
                            'target' => 'openModal',
                        ],
                        [
                            'provider' => 'feature_form.areas.fields.'
                                . 'fields.'
                                . 'simpify_management_feature_fields_update_modal.'
                                . 'update_simpify_management_feature_fields_form_loader',
                            'target' => 'render',
                            'params' => [
                                'field_id' => $item['entity_id'],
                            ],
                        ]
                    ],
                    'href' => '#',
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$name]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        self::FEATURE_FIELD_PATH_DELETE,
                        ['id' => $item['entity_id']]
                    ),
                    'label' => __('Delete'),
                    'isAjax' => true,
                    'confirm' => [
                        'title' => __('Delete Field'),
                        'message' => __('Are you sure you want to delete the select Field?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
