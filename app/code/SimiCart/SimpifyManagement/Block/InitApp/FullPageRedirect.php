<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Block\InitApp;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use SimiCart\SimpifyManagement\Exceptions\MissingAuthUrlException;
use SimiCart\SimpifyManagement\Exceptions\SignatureVerificationException;
use SimiCart\SimpifyManagement\Model\ConfigProvider;
use SimiCart\SimpifyManagement\Model\InstallShop;

class FullPageRedirect extends Template
{
    protected ConfigProvider $configProvider;
    protected InstallShop $installShop;

    /**
     * @param ConfigProvider $configProvider
     * @param Context $context
     * @param InstallShop $installShop
     * @param array $data
     */
    public function __construct(
        ConfigProvider $configProvider,
        Template\Context $context,
        InstallShop $installShop,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->installShop = $installShop;
    }

    public function getApiKey(): string
    {
        return $this->configProvider->getApiKey();
    }
    public function getHost(): string
    {
        return $this->getRequest()->getParam('host');
    }

    public function getShop(): string
    {
        return $this->getRequest()->getParam('shop');
    }

    /**
     * Get code param
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->getRequest()->getParam('code');
    }

    /**
     * Authenticate shop and return auth url
     *
     * @return string
     * @throws SignatureVerificationException|MissingAuthUrlException
     */
    public function getAuthUrl(): string
    {
        [$result, $status] = $this->authenticateShop($this->getShop(), $this->getCode());

        if ($status === null) {
            throw new SignatureVerificationException(__('Invalid HMAC verification'));
        }

        if (!$result['url']) {
            throw new MissingAuthUrlException(__('Missing auth url'));
        }

        return $result['url'];
    }

    public function getAppBridgeVersion(): string
    {
        return $this->configProvider->getAppBridgeVersion();
    }

    /**
     * Authenticates a shop. ((not yet) and fires post authentication actions.)
     *
     * @param string $shopDomain
     * @param string|null $code
     * @return array|void
     */
    protected function authenticateShop(string $shopDomain, ?string $code = null)
    {
        $result = $this->installShop->execute($shopDomain, $code);
        if (!$result['completed']) {
            // No code, redirect to auth URL
            return [$result, false];
        }
    }
}
