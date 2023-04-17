<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface as IShopRepository;
use SimiCart\SimpifyManagement\Helper\UtilTrait;

class SimpifyShop implements ResolverInterface
{
    use UtilTrait;

    private IShopRepository $shopRepository;

    /**
     * @param IShopRepository $shopRepository
     */
    public function __construct(
        IShopRepository $shopRepository
    ) {
        $this->shopRepository = $shopRepository;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        try {
            if ($context->getExtensionAttributes()->getIsSimpifyShop()) {
                $shopId = $context->getExtensionAttributes()->getSimpifyShopId();
                return $this->formatShopOuput($this->shopRepository->getById($shopId));
            }
        } catch (\Exception $e) {
            throw new GraphQlNoSuchEntityException(__("Failed to fetch Shop Info."));
        }
        throw new GraphQlAuthorizationException(__("Unauthorized Shop!"));
    }

    /**
     * Format shop output for graphql type
     *
     * @param IShop $shop
     * @return array
     */
    protected function formatShopOuput(IShop $shop)
    {
        return $shop->getData() +
//            $shop->getArrayMoreInfo() +
            [
                "model" => $shop,
                "uid" => $this->base64UrlEncode($shop->getShopDomain()),
            ];
    }
}
