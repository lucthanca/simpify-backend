<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

// import app factory
use SimiCart\SimpifyManagement\Model\AppFactory;

class CreateApp implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private AppFactory $appFactory;

    /**
     * CreateApp constructor.
     *
     * @param AppFactory $appFactory
     */
    public function __construct(
        AppFactory $appFactory
    ) {
        $this->appFactory = $appFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        // TODO: Implement resolve() method.
    }
}
