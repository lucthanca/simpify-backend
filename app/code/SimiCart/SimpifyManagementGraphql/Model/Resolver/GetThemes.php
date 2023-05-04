<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;
use SimiCart\SimpifyManagement\Model\ResourceModel\Theme\CollectionFactory;
use SimiCart\SimpifyManagementGraphql\Model\Formatter\ThemeFormatterTrait;

class GetThemes implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    use ThemeFormatterTrait;

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
            $result[] = $this->formatOutput($item);
        }

        return $result;
    }
}
