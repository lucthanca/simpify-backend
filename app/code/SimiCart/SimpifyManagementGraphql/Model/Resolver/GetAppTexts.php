<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage\CollectionFactory;

class GetAppTexts implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private \SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage\CollectionFactory $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \SimiCart\SimpifyManagement\Model\ResourceModel\DefaultLanguage\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        return $this->getAllTexts();
    }

    /**
     * Get all texts
     *
     * @return array
     */
    protected function getAllTexts(): array
    {
        $collection = $this->collectionFactory->create();
        return array_map(function ($item) {
            return $item['text'];
        }, $collection->getData());
    }

    /**
     * Get collection factory
     *
     * @return CollectionFactory
     */
    public function getCollectionFactory(): CollectionFactory
    {
        return $this->collectionFactory;
    }
}
