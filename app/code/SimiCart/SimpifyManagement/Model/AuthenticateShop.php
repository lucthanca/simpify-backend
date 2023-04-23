<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use SimiCart\SimpifyManagement\Api\Data\ShopInterface as IShop;
use SimiCart\SimpifyManagement\Exceptions\SignatureVerificationException;

/**
 * Verify and authenticate shop
 */
class AuthenticateShop
{
    private InstallShop $installShop;
    private IRequest $request;
    private ManagerInterface $eventManager;
    private \Psr\Log\LoggerInterface $logger;
    private VerifyShopify $verifyShopify;

    /**
     * Authenticate Shop Constructor
     *
     * @param InstallShop $installShop
     * @param IRequest $request
     * @param ManagerInterface $eventManager
     * @param LoggerInterface $logger
     * @param VerifyShopify $verifyShopify
     */
    public function __construct(
        InstallShop $installShop,
        IRequest $request,
        ManagerInterface $eventManager,
        \Psr\Log\LoggerInterface $logger,
        VerifyShopify $verifyShopify
    ) {
        $this->installShop = $installShop;
        $this->request = $request;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
        $this->verifyShopify = $verifyShopify;
    }

    /**
     * Execute authenticate and update shop info to local system
     *
     * @param IShop $shop
     * @param string|null $code
     * @return bool
     * @throws SignatureVerificationException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(IShop $shop, ?string $code = null)
    {
        // If no code => on install app. do nothing, let block build auth url
        if (empty($code)) {
            return false;
        }
        $this->verifyShopify->execute($this->getRequest());
        try {
            $this->installShop->execute($shop, $code);
        } catch (\Exception $e) {
            $this->logger->critical("Install Shop FAILED: " . $e);
            return false;
        }
        $this->eventManager->dispatch('shop_authenticated_success', ['shop' => $shop]);
        return true;
    }

    /**
     * Get request
     *
     * @return IRequest
     */
    public function getRequest(): IRequest
    {
        return $this->request;
    }

    /**
     * Get install shop action
     *
     * @return InstallShop
     */
    public function getInstallShop(): InstallShop
    {
        return $this->installShop;
    }
}
