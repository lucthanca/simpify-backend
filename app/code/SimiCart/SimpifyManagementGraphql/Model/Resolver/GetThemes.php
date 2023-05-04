<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;
use SimiCart\SimpifyManagement\Model\ResourceModel\Theme\CollectionFactory;

class GetThemes implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private CollectionFactory $themeCollectionFactory;

    /**
     * GetThemes constructor.
     *
     * @param CollectionFactory $themeCollectionFactory
     */
    public function __construct(
        CollectionFactory $themeCollectionFactory
    ) {
        $this->themeCollectionFactory = $themeCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $collection = $this->themeCollectionFactory->create()->addFieldToFilter(ThemeInterface::STATUS, 1);
        $result = [];
        foreach ($collection->getItems() as $item) {
            $resultI = [
                'model' => $item,
            ];
            $this->formatOutput($resultI);
            $result[] = $resultI;
        }

        return $result;
    }

    /**
     * Format data item output
     *
     * @param array $data
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function formatOutput(array &$data)
    {
        /** @var ThemeInterface $i */
        $i = $data['model'];
        $data = [
            'uid' => base64_encode($i->getId()),
            'name' => $i->getName(),
            'is_active' => (bool) $i->getStatus(),
            'image' => $i->getImageUrl(),
            'preview_images' => $i->getPreviewImagesAsArray(),
            'model' => $i,
        ];
    }
}