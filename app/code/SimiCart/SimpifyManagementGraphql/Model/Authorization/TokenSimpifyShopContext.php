<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Authorization;

use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTime as Date;
use Magento\Framework\Webapi\Request;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Helper\Oauth\Data as OauthHelper;
use Magento\Integration\Model\Oauth\Token;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Webapi\Model\Authorization\TokenUserContext;
use SimiCart\SimpifyManagementGraphql\Helper\OathConfig;
use SimiCart\SimpifyManagementGraphql\Model\ShopContextInterface;

class TokenSimpifyShopContext extends TokenUserContext implements ShopContextInterface
{
    private ?DateTime $dateTime;
    private ?Date $date;
    private OathConfig $oathConfig;

    /**
     * TokenSimpifyShopContext constructor
     *
     * @param Request $request
     * @param TokenFactory $tokenFactory
     * @param IntegrationServiceInterface $integrationService
     * @param OathConfig $oathConfig
     * @param DateTime|null $dateTime
     * @param Date|null $date
     * @param OauthHelper|null $oauthHelper
     */
    public function __construct(
        Request $request,
        TokenFactory $tokenFactory,
        IntegrationServiceInterface $integrationService,
        OathConfig $oathConfig,
        DateTime $dateTime = null,
        Date $date = null,
        OauthHelper $oauthHelper = null
    ) {
        parent::__construct($request, $tokenFactory, $integrationService, $dateTime, $date, $oauthHelper);
        $this->dateTime = $dateTime;
        $this->date = $date;
        $this->oathConfig = $oathConfig;
    }

    /**
     * Auth shop id
     *
     * @var int
     */
    protected ?int $shopId = null;

    /**
     * @inheritDoc
     */
    public function getSimpifyShopId(): ?int
    {
        $this->processRequest();
        return $this->shopId;
    }

    /**
     * Set user data based on user type received from token data.
     *
     * Custom for shopify shop type
     *
     * @param Token $token
     * @return void
     */
    protected function setUserDataViaToken(Token $token)
    {
        $this->userType = $token->getUserType();

        // Set shop id for auth context
        if ((int) $this->userType === self::USER_TYPE_SIMPIFY_SHOP) {
            if ($this->isShopTokenExpired($token)) {
                $this->isRequestProcessed = true;
                return;
            }

            $this->shopId = (int) $token->getSimpifyShopId();
            /**
             * Set false for bypass condition check at CompositeUserContext->chosenUserContext
             *
             * @see \Magento\Authorization\Model\CompositeUserContext Line: 84
             */
            $this->userId = false;
            return;
        }

        parent::setUserDataViaToken($token);
    }

    /**
     * Check if shop token is expired
     *
     * @param Token $token
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    private function isShopTokenExpired(Token $token): bool
    {
        return false;
        // For current concept, it will not be expired
        // $tokenTtl = $this->oathConfig->getSimpifyShopTokenLifetime();

        // if (empty($tokenTtl)) {
        //     return false;
        // }

        // if ($this->dateTime->strToTime($token->getCreatedAt()) < ($this->date->gmtTimestamp() - $tokenTtl * 3600)) {
        //     return true;
        // }

        // return false;
    }
}
