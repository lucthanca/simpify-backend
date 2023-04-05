<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\Clients;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class Rest
{
    /**
     * Additional Guzzle options.
     *
     * @var array
     */
    protected $guzzleOptions = [
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'timeout' => 10.0,
        'max_retry_attempts' => 2,
        'default_retry_multiplier' => 2.0,
        'retry_on_status' => [429, 503, 500],
    ];

    private ?Uri $baseUri = null;
    private ?string $shopDomain;
    private ClientOptions $options;
    private Client $client;

    /**
     * @param string|null $shopDomain
     * @param array|null $options
     */
    public function __construct(?string $shopDomain = null, array $options = [])
    {
        $this->shopDomain = $shopDomain;
        $this->options = new ClientOptions($options);
        $this->client = new Client($this->guzzleOptions);
    }

    /**
     * Gets the auth URL for Shopify to allow the user to accept the app (for public apps).
     *
     * @param string|array $scopes      The API scopes as a comma seperated string or array.
     * @param string       $redirectUri The valid redirect URI for after acceptance of the permissions.
     *                                  It must match the redirect_uri in your app settings.
     * @param string       $mode        The API access mode, offline or per-user.
     *
     * @throws \Exception For missing API key.
     *
     * @return string Formatted URL.
     */
    public function getAuthUrl($scopes, string $redirectUri, string $mode = 'offline'): string
    {
        if ($this->getOptions()->getApiKey() === null) {
            throw new \Exception('API key is missing');
        }
        if (is_array($scopes)) {
            $scopes = implode(',', $scopes);
        }
        $query = [
            'client_id' => $this->getOptions()->getApiKey(),
            'scope' => $scopes,
            'redirect_uri' => $redirectUri,
        ];
        if ($mode !== null && $mode !== 'offline') {
            $query['grant_options'] = [$mode];
        }
        return (string) $this->getBaseUri()
            ->withPath("/admin/oauth/authorize")
            ->withQuery(preg_replace('/%5B\d+%5D/', '%5B%5D', http_build_query($query)));
    }

    /**
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function requestAccess(string $code): array
    {
        if ($this->getOptions()->getApiSecret() === null || $this->getOptions()->getApiKey() === null) {
            // Key and secret required
            throw new \Exception('API key or secret is missing');
        }
        // Do a JSON POST request to grab the access token
        $url = $this->getBaseUri()->withPath('/admin/oauth/access_token');
        $data = [
            'json' => [
                'client_id' => $this->getOptions()->getApiKey(),
                'client_secret' => $this->getOptions()->getApiSecret(),
                'code' => $code,
            ],
        ];
        try {
            $response = $this->getClient()->post($url, $data);
            return $this->responseToArray($response->getBody()->getContents());
        } catch (\Exception $e) {
            $body = json_decode($e->getResponse()->getBody()->getContents());
            throw new \Exception($body->error_description);
        }
    }

    public function getShopInfo()
    {
        if ($this->getOptions()->getApiSecret() === null || $this->getOptions()->getApiKey() === null) {
            // Key and secret required
            throw new \Exception('API key or secret is missing');
        }
        // Do a JSON POST request to grab the access token
        $url = $this->getBaseUri()->withPath('/admin/api/2023-01/shop.json');
        try {
            $response = $this->getClient()->get($url, ["headers" => ["X-Shopify-Access-Token" => $this->getOptions()->getShop()->getAccessToken()]]);
            return $this->responseToArray($response->getBody()->getContents());
        } catch (\Exception $e) {
            $body = json_decode($e->getResponse()->getBody()->getContents());
            throw new \Exception($body->error_description);
        }
    }

    /**
     * Decode response body to array
     *
     * @param mixed $body
     * @return array
     */
    private function responseToArray($body): array
    {
        return json_decode($body, true, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * Set base uri for client
     *
     * @param string|null $uri
     * @return $this
     */
    public function setShopDomain(?string $uri): Rest
    {
        $this->shopDomain = $uri;
        return $this;
    }

    /**
     * Get client base uri based on shop domain
     *
     * @return Uri
     * @throws \Exception
     */
    public function getBaseUri(): Uri
    {
        if ($this->shopDomain === null) {
            // Shop is required
            throw new \Exception('Shopify domain missing for API calls');
        }

        if (is_null($this->baseUri)) {
            $this->baseUri = new Uri("https://{$this->shopDomain}");
        }
        return $this->baseUri;
    }

    /**
     * Set client base uri
     *
     * @param Uri $uri
     * @return $this
     */
    public function setBaseUri(Uri $uri): Rest
    {
        $this->baseUri = $uri;
        return $this;
    }

    /**
     * @return ClientOptions
     */
    public function getOptions(): ClientOptions
    {
        return $this->options;
    }

    /**
     * Set options for client
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): Rest
    {
        $this->getOptions()->setData(array_merge($this->getOptions()->getData(), $options));
        return $this;
    }

    /**
     * Get the client
     *
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
