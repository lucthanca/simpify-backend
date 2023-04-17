<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;
use SimiCart\SimpifyManagementGraphql\Exceptions\GraphQlUncommonErrorException;
use SimiCart\SimpifyManagementGraphql\Model\Formatter\SimpifyShopFormatter;

class UpdateShopInformation implements ResolverInterface
{
    private ShopRepositoryInterface $shopRepository;
    private SimpifyShopFormatter $shopFormatter;
    private \Psr\Log\LoggerInterface $logger;

    /**
     * UpdateShopInformation constructor
     *
     * @param ShopRepositoryInterface $shopRepository
     * @param SimpifyShopFormatter $shopFormatter
     * @param LoggerInterface $logger
     */
    public function __construct(
        ShopRepositoryInterface $shopRepository,
        SimpifyShopFormatter $shopFormatter,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->shopRepository = $shopRepository;
        $this->shopFormatter = $shopFormatter;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$context->getExtensionAttributes()->getIsSimpifyShop()) {
            throw new GraphQlAuthorizationException(__("Unauthorized!!!"));
        }

        try {
            $this->validateInput($args);
            $shopId = $context->getExtensionAttributes()->getSimpifyShopId();
            $shop = $this->shopRepository->getById($shopId);
            if (isset($args['input']['more_info'])) {
                $shop->setMoreInfo($args['input']['more_info']);
            }
            $this->shopRepository->save($shop);
            return $this->shopFormatter->formatToOutput($this->shopRepository->getById($shopId));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new GraphQlUncommonErrorException(
                __("Something went wrong. Please reload page, if it's still happen, please contact us!")
            );
        }
    }

    /**
     * Validate input
     *
     * @param array $args
     * @return void
     * @throws GraphQlInputException
     */
    protected function validateInput(array $args)
    {
        if (!isset($args['input'])) {
            throw new GraphQlInputException(__("input is required!"));
        }

        if (isset($args['input']['more_info'])) {
            $moreInfo = $args['input']['more_info'];

            if (empty($moreInfo['shop_owner_email'])) {
                throw new GraphQlInputException(__("Email is required!"));
            }

            if (!filter_var($moreInfo['shop_owner_email'], FILTER_VALIDATE_EMAIL)) {
                throw new GraphQlInputException(__("Invalid email format!"));
            }

            if (empty($moreInfo['shop_owner_name'])) {
                throw new GraphQlInputException(__("Email is required!"));
            }
            if (empty($moreInfo['industry'])) {
                throw new GraphQlInputException(__("Industry is required!"));
            }
        }
    }
}
