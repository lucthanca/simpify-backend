<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api;

interface ShopApiInterface
{
    /**
     * Build the authentication URL to Shopify.
     *
     * @param int $authMode   The mode of authentication (offline or per-user).
     * @param string $scopes The scopes for the authentication, comma-separated.
     *
     * @return string
     */
    public function buildAuthUrl(int $authMode, string $scopes): string;

    /**
     * Finish the process by getting the access details from the code.
     *
     * @param string $code The code from the request.
     *
     * @return array
     */
    public function getAccessData(string $code): array;
    public function getShopInfo(): array;
}