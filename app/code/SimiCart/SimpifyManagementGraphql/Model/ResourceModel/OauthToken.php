<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\ResourceModel;

class OauthToken extends \Magento\Integration\Model\ResourceModel\Oauth\Token
{
    /**
     * Remove token by simpify shop id
     *
     * @param int $id
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeBySimpifyShopId(int $id)
    {
        $connection = $this->getConnection();
        return $connection->delete($this->getMainTable(), $connection->quoteInto("simpify_shop_id = ?", $id));
    }
}
