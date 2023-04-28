<?php

namespace SimiCart\SimpifyManagement\Ui\Component\DefaultLanguage\Listing\Column;

use SimiCart\SimpifyManagement\Ui\Component\Listing\Column\Actions as Column;

class Actions extends Column
{
    const FEATURE_PATH_DELETE = 'simpify/apptext/delete';

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
                        'simpify/apptext/edit',
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
                        'title' => __('Delete Text'),
                        'message' => __('Are you sure you want to delete the select text?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
