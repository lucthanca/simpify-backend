<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;
use SimiCart\SimpifyManagement\Model\Session;
use SimiCart\SimpifyManagement\Registry\CurrentShop;

class Dashboard extends \Magento\Framework\View\Element\Template
{
    private Session $shopSession;
    protected CurrentShop $currentShop;

    /**
     * Dashboard constructor
     *
     * @param Context $context
     * @param Session $shopSession
     * @param CurrentShop $currentShop
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Session $shopSession,
        CurrentShop $currentShop,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->shopSession = $shopSession;
        $this->currentShop = $currentShop;
    }

    /**
     * Get logged in shop
     *
     * @return IShop
     */
    public function getShop(): IShop
    {
        return $this->currentShop->get();
    }
}
