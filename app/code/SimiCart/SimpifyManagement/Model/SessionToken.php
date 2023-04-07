<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model;

use Magento\Framework\Exception\LocalizedException;

class SessionToken
{
    /**
     * The regex for the format of the JWT.
     *
     * @var string
     */
    public const TOKEN_FORMAT = '/^eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9\.[A-Za-z0-9\-\_=]+\.[A-Za-z0-9\-\_\=]*$/';

    protected string $string;
    /**
     * @var ?string
     */
    private $shopDomain;

    public function __construct(string $token, bool $verifyToken = true)
    {
        $this->string = $token;
        $this->decodeToken();
    }

    /**
     * Decode and validate the formatting of the token.
     *
     * @throws AssertionFailedException If token is malformed.
     *
     * @return void
     */
    protected function decodeToken(): void
    {
        if (!preg_match(self::TOKEN_FORMAT, $this->string)) {
            throw new LocalizedException(__('Session token is malformed.'));
        }
        // Decode the token
        $this->parts = explode('.', $this->string);
        $body = json_decode(SessionToken::base64UrlDecode($this->parts[1]), true);
        // Confirm token is not malformed
        foreach ([$body['iss'], $body['dest'], $body['aud'], $body['sub'], $body['exp'], $body['nbf'], $body['iat'], $body['jti'], $body['sid']] as $value) {
            if (is_null($value)) {
                throw new LocalizedException(__('Session token is malformed.'));
            }
        }
        $this->iss = $body['iss'];
        $this->dest = $body['dest'];
        $this->aud = $body['aud'];
        $this->sub = $body['dest'];
        $this->jti = $body['dest'];
        $this->sid = $body['sid'];
        $this->exp = $body['exp'];
        $this->nbf = $body['nbf'];
        $this->iat = $body['iat'];

        $host = parse_url($body['dest'], PHP_URL_HOST);
        $this->shopDomain = $host;
    }

    /**
     * URL-safe Base64 decoding.
     *
     * Replaces `-` with `+` and `_` with `/`.
     *
     * Adds padding `=` if needed.
     *
     * @param string $data The data to be decoded.
     *
     * @return string
     */
    public static function base64UrlDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * Get token shop string
     *
     * @return string|null
     */
    public function getShopDomain(): ?string
    {
        return $this->shopDomain;
    }
}
