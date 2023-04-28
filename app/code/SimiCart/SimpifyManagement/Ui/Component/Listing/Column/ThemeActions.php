<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Listing\Column;

use SimiCart\SimpifyManagement\Ui\Component\Listing\Column\Actions as Column;

class ThemeActions extends Column
{
    const THEME_PATH_EDIT = 'simpify/theme/edit';
    const THEME_PATH_DELETE = 'simpify/theme/delete';

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
                        self::THEME_PATH_EDIT,
                        ['id' => $item['entity_id']]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        self::THEME_PATH_DELETE,
                        ['id' => $item['entity_id']]
                    ),
                    'label' => __('Delete'),
                    'isAjax' => true,
                    'confirm' => [
                        'title' => __('Delete Theme'),
                        'message' => __('Are you sure you want to delete the selected Theme?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
