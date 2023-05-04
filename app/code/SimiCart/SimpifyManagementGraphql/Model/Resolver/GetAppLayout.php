<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\AppLayoutRepositoryInterface;
use SimiCart\SimpifyManagement\Api\Data\AppInterface;
use SimiCart\SimpifyManagement\Api\Data\AppLayoutInterface;

class GetAppLayout implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private AppLayoutRepositoryInterface $appLayoutRepository;

    /**
     * GetAppLayout constructor.
     *
     * @param AppLayoutRepositoryInterface $appLayoutRepository
     */
    public function __construct(
        \SimiCart\SimpifyManagement\Api\AppLayoutRepositoryInterface $appLayoutRepository
    ) {
        $this->appLayoutRepository = $appLayoutRepository;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!empty($value['model'])) {
            throw new GraphQlInputException(__("App not found!"));
        }
        /** @var AppInterface $app */
        $app = $value['model'];

        $appLayout = $app->getAppLayout();
        if (!$appLayout->getId()) {
            return null;
        }

        return $this->formatOutput($appLayout);
    }

    protected function formatOutput(AppLayoutInterface $appLayout)
    {
        return [
            'uid' => base64_encode($appLayout->getId()),
            'font' => $appLayout->getFont(),
            'colors' => $appLayout->getDecodedColors(),
            'menu' => $appLayout->getMenu(),
            'homepages' => $appLayout->getHomePage(),
            'collection_page' => $appLayout->getCollectionPage(),
            'product_page' => $appLayout->getProductPage(),
            'landing_page' => $appLayout->getLandingPage(),
            'model' => $appLayout,
        ];
    }
}
