<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\App\RequestInterfaceFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Url;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Model\VerifyShopify;

class AuthenticateShop implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private RequestInterfaceFactory $requestFactory;
    private VerifyShopify $verifyShopify;
    private \Psr\Log\LoggerInterface $logger;
    private Url $urlBuilder;
    private StoreManagerInterface $storeManager;

    /**
     * @param RequestInterfaceFactory $requestFactory
     * @param VerifyShopify $verifyShopify
     * @param LoggerInterface $logger
     * @param Url $urlBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterfaceFactory $requestFactory,
        VerifyShopify $verifyShopify,
        \Psr\Log\LoggerInterface $logger,
        Url $urlBuilder,
        StoreManagerInterface $storeManager
    ) {

        $this->requestFactory = $requestFactory;
        $this->verifyShopify = $verifyShopify;
        $this->logger = $logger;
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $request = $this->requestFactory->create();
        $request->setParams($args['param']);

        $result = [
            'success'  => true
        ];
        try {
            [$statusCode, $data] = $this->verifyShopify->execute($request);
            if ($statusCode === 'new_shop') {
                $targetStore = $this->storeManager->getStore((int) $this->storeManager->getDefaultStoreView()->getId());
                $result['redirect_url'] = $this->urlBuilder->setScope($targetStore)
                    ->getUrl('simpify/initapp/requestinstall', ['_query' => $args['param'], '_nosid' => true]);
                $result['type'] = 'installation';
                $result['message'] = __("Redirect to installation page...");
            }
        } catch (\Exception $e) {
            dd($e);
            $this->logger->critical('Verify Shop FAILED: ' . $e);

        }

        return $result;
    }
}
