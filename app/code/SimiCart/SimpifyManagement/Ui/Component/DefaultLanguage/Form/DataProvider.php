<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\DefaultLanguage\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use SimiCart\SimpifyManagement\Api\Data\DefaultLanguageInterface as IDefaultLanguage;
use SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;
    private DataPersistorInterface $dataPersistor;
    private RequestInterface $request;

    /**
     * Data Provider constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param RequestInterface $request
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Provide data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /**
         * @var IDefaultLanguage $textItem
         */
        foreach ($items as $textItem) {
            $this->loadedData[$textItem->getId()] = $textItem->getData();
        }
        $data = $this->dataPersistor->get('apptext_');
        if (!empty($data)) {
            $this->loadedData[$textItem->getId()] = $data;
            $this->dataPersistor->clear('apptext_');
        }
        return $this->loadedData;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        $this->meta = parent::getMeta();
        $this->createTextDynamicRows();
        $this->createTextField();

        return $this->meta;
    }

    /**
     * Get text dynamic rows config for only add new text
     *
     * @return void
     */
    public function createTextDynamicRows(): void
    {
        if ($this->request->getParam($this->getRequestFieldName())) {
            return;
        }
        $this->meta['text_fieldset'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'fieldset',
                        'label' => __('Default Text'),
                        'dataScope' => null,
                        'collapsible' => false,
                        'sortOrder' => 100,
                    ]
                ]
            ],
            'children' => [
                'text' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'addButtonLabel' => __('Add Text'),
                                'label' => __('Text'),
                                'componentType' => 'dynamicRows',
                                'sortOrder' => 10,
                                'additionalClasses' => 'admin__field-wide'
                            ]
                        ]
                    ],
                    'children' => [
                        'record' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'container',
                                        'component' => 'Magento_Ui/js/dynamic-rows/record',
                                        'isTemplate' => true,
                                        'is_collection' => true,
                                        'showFallbackReset' => false,
                                    ]
                                ]
                            ],
                            'children' => [
                                'text' => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'formElement' => 'input',
                                                'componentType' => 'field',
                                                'dataType' => 'text',
                                                'label' => __('Text'),
                                                'dataScope' => 'text',
                                                'validation' => [
                                                    'required-entry' => true,
                                                    'max_text_length' => 255
                                                ],
                                                'additionalClasses' => true,
                                                'sortOrder' => 10,
                                                'fit' => false,
                                            ]
                                        ]
                                    ]
                                ],
                                'is_delete' => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'componentType' => 'actionDelete',
                                                'fit' => false,
                                                'sortOrder' => 100,
                                                'additionalClasses' => 'data-grid-actions-cell',
                                                'label' => __('Actions'),
                                            ]
                                        ]
                                    ]
                                ],
                            ],
                        ]
                    ],
                ]
            ]
        ];
    }

    /**
     * Create text field for only edit text
     *
     * @return void
     */
    public function createTextField()
    {
        if ($this->request->getParam($this->getRequestFieldName())) {
            $this->meta['general'] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => 'fieldset',
                            'label' => null,
                            'dataScope' => null,
                            'collapsible' => false,
                            'sortOrder' => 10,
                        ]
                    ]
                ],
                'children' => [
                    'text' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement' => 'input',
                                    'componentType' => 'field',
                                    'dataType' => 'text',
                                    'label' => __('Default Text'),
                                    'dataScope' => 'text',
                                    'validation' => [
                                        'required-entry' => true,
                                        'max_text_length' => 255
                                    ],
                                    'additionalClasses' => true,
                                    'sortOrder' => 10,
                                    'fit' => false,
                                    'source' => 'apptext',
                                ]
                            ]
                        ]
                    ]
                ],
            ];
        }
    }
}
