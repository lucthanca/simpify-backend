<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model;

use Magento\Integration\Model\Oauth\Token;

class OauthToken extends Token
{
    /**
     * Create Simpify Shop token
     *
     * @param int $id Shimpify Shop ID
     * @return $this
     */
    public function createSimpifyShopToken(int $id)
    {
        $this->setSimpifyShopId($id);
        return $this->saveAccessToken(ShopContextInterface::USER_TYPE_SIMPIFY_SHOP);
    }
}
