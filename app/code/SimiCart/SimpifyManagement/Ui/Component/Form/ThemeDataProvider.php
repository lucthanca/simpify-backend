<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
// import Theme collection factory and theme interface
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface as ITheme;
use SimiCart\SimpifyManagement\Model\ResourceModel\Theme\CollectionFactory;

/**
 * Form theme data provider
 */
class ThemeDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    // construct all imported classes
    protected $loadedData;
    private DataPersistorInterface $dataPersistor;
    private RequestInterface $request;
    private CollectionFactory $collectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param RequestInterface $request
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
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
        // set collection
        $this->collection = $collectionFactory->create();
        // set data persistor
        $this->dataPersistor = $dataPersistor;
        // set request
        $this->request = $request;
        // set collection factory
        $this->collectionFactory = $collectionFactory;
        // set parent
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        // if loaded data is set, return loaded data
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        // get all items from collection
        $items = $this->collection->getItems();
        // set loaded data
        $this->loadedData = [];
        // if items is not empty
        if (!empty($items)) {
            // foreach items as item
            foreach ($items as $item) {
                // get item data
                $data = $item->getData();
                // if image is not empty
                if ($data[ITheme::IMAGE]) {
                    // set image
                    $data[ITheme::IMAGE] = [
                        [
                            'name' => $data[ITheme::IMAGE],
                            'url' => $item->getImageUrl(),
                            'size' => $item->getThemeImageSize()
                        ]
                    ];
                }
                // set loaded data
                $this->loadedData[$item->getId()] = $data;
            }
        }
        // get data persistor
        $data = $this->dataPersistor->get('simicart_simpifymanagement_theme');
        // if data is not empty
        if (!empty($data)) {
            // get theme collection
            $items = $this->collectionFactory->create()->addFieldToFilter(ITheme::ID, $data);
            // foreach items as item
            foreach ($items as $item) {
                // get item data
                $data = $item->getData();
                // if image is not empty
                if ($data[ITheme::IMAGE]) {
                    // set image
                    $data[ITheme::IMAGE] = [
                        [
                            'name' => $data[ITheme::IMAGE],
                            'url' => $this->getMediaUrl() . $data[ITheme::IMAGE]
                        ]
                    ];
                }
                // set loaded data
                $this->loadedData[$item->getId()] = $data;
            }
            // unset data persistor
            $this->dataPersistor->clear('simicart_simpifymanagement_theme');
        }
        // return loaded data
        return $this->loadedData;
    }
}
