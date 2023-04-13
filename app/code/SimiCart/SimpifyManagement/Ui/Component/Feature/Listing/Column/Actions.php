<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Feature\Listing\Column;

use SimiCart\SimpifyManagement\Ui\Component\Listing\Column\Actions as Column;

class Actions extends Column
{
    const FEATURE_PATH_DELETE = 'simpify/features/delete';

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'simpify/features/edit',
                        ['id' => $item['entity_id']]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        self::FEATURE_PATH_DELETE,
                        ['id' => $item['entity_id']]
                    ),
                    'label' => __('Delete'),
                    'isAjax' => true,
                    'confirm' => [
                        'title' => __('Delete Feature'),
                        'message' => __('Are you sure you want to delete the select Feature?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
