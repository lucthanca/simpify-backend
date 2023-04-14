<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use SimiCart\SimpifyManagement\Helper\UtilTrait;
use SimiCart\SimpifyManagement\Model\ConfigProvider;

class Actions extends Column
{
    use UtilTrait;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    protected ConfigProvider $configProvider;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ConfigProvider $configProvider
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        ConfigProvider $configProvider,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->configProvider = $configProvider;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

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
                $hmac = $this->createHmac(
                    ['data' => ['id' => $item['entity_id']], 'buildQuery' => true],
                    $this->configProvider->getApiSecret()
                );
                $item[$this->getData('name')]['admin_login'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'simpify/authenticate/adminlogin',
                        ['id' => $item['entity_id'], 'hmac' => $hmac]
                    ),
                    'target' => '_blank',
                    'label' => __('Login'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
