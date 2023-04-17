<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface as IShopRepository;
use SimiCart\SimpifyManagement\Helper\UtilTrait;
use SimiCart\SimpifyManagementGraphql\Model\Formatter\SimpifyShopFormatter;

class SimpifyShop implements ResolverInterface
{
    use UtilTrait;

    private IShopRepository $shopRepository;
    private SimpifyShopFormatter $shopFormatter;

    /**
     * SimpifyShop constructor
     *
     * @param IShopRepository $shopRepository
     * @param SimpifyShopFormatter $shopFormatter
     */
    public function __construct(
        IShopRepository $shopRepository,
        SimpifyShopFormatter $shopFormatter
    ) {
        $this->shopRepository = $shopRepository;
        $this->shopFormatter = $shopFormatter;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        try {
            if ($context->getExtensionAttributes()->getIsSimpifyShop()) {
                $shopId = $context->getExtensionAttributes()->getSimpifyShopId();
                return $this->shopFormatter->formatToOutput($this->shopRepository->getById($shopId));
            }
        } catch (\Exception $e) {
            throw new GraphQlNoSuchEntityException(__("Failed to fetch Shop Info."));
        }
        throw new GraphQlAuthorizationException(__("Unauthorized Shop!"));
    }
}
