<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class OathConfig
{
    private ScopeConfigInterface $scopeConfig;

    /**
     * OathConfig constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get simpify shop token lifetime from config.
     *
     * @return float hours
     */
    public function getSimpifyShopTokenLifetime()
    {
        $hours = $this->scopeConfig->getValue('oauth/access_token_lifetime/access_token_lifetime');
        return is_numeric($hours) && $hours > 0 ? $hours : 0;
    }
}
