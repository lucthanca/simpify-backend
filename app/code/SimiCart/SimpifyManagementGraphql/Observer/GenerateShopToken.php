<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;
use SimiCart\SimpifyManagementGraphql\Model\OauthTokenFactory;
use Magento\Integration\Model\ResourceModel\Oauth\Token\CollectionFactory as TokenCollectionFactory;

class GenerateShopToken implements ObserverInterface
{
    private OauthTokenFactory $oauthTokenFactory;
    private TokenCollectionFactory $tokenCollectionFactory;

    /**
     * GenerateShopToken Constructor
     *
     * @param OauthTokenFactory $oauthTokenFactory
     * @param TokenCollectionFactory $tokenCollectionFactory
     */
    public function __construct(
        OauthTokenFactory $oauthTokenFactory,
        TokenCollectionFactory $tokenCollectionFactory
    ) {
        $this->oauthTokenFactory = $oauthTokenFactory;
        $this->tokenCollectionFactory = $tokenCollectionFactory;
    }

    /**
     * Create token for shop
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var IShop $shop */
        $shop = $observer->getData('shop');
        if (!$shop || !$shop->getId()) {
            return;
        }

        if ($this->shopHasToken((int) $shop->getId())) {
            return;
        }

        $this->oauthTokenFactory->create()->createSimpifyShopToken((int) $shop->getId());
    }

    /**
     * Check if the shop has a token or not
     *
     * @param int $shopId
     * @return true
     * @throws LocalizedException
     */
    public function shopHasToken(int $shopId): bool
    {
        $tokenCollection = $this->tokenCollectionFactory->create()
            ->addFilter('main_table.simpify_shop_id', $shopId);
        if ($tokenCollection->getSize() > 1) {
            try {
                foreach ($tokenCollection as $token) {
                    $token->delete();
                }
            } catch (\Exception $e) {
                throw new LocalizedException(__("Can not remove token."));
            }
            return false;
        }
        return $tokenCollection->getSize() === 1;
    }
}
