<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\FeatureField\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{

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
            }
        }

        return $dataSource;
    }
}
