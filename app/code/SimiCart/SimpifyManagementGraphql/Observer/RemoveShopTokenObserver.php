<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagementGraphql\Model\ResourceModel\OauthToken;

/**
 * Observes the `shop_uninstalled_success` event.
 */
class RemoveShopTokenObserver implements ObserverInterface
{
    private OauthToken $oauthTokenResource;
    private \Psr\Log\LoggerInterface $logger;

    /**
     * @param OauthToken $oauthTokenResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        OauthToken $oauthTokenResource,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->oauthTokenResource = $oauthTokenResource;
        $this->logger = $logger;
    }

    /**
     * Observer for shop_uninstalled_success.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var \SimiCart\SimpifyManagement\Api\Data\ShopInterface $shop */
        $shop = $observer->getData('shop');
        try {
            $this->oauthTokenResource->removeBySimpifyShopId((int) $shop->getId());
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

    }
}
