<?php

use Exception;

class ArCaptcha
{
    /**
     * Api Base Uri
     * @var string
     */
    protected $api_base_uri = 'https://api.arcaptcha.ir/arcaptcha/api/';

    /**
     * Script Url
     * @var string
     */
    protected $script_url = 'https://widget.arcaptcha.ir/1/api.js';

    /**
     * User Site Key
     * @var string
     */
    protected $site_key;

    /**
     * User Secret Key
     * @var string
     */
    protected $secret_key;

    /**
     * Http Adapter
     * @var Http
     */
    protected $http;

    /**
     * ArCaptcha Constructor
     * @param string $site_key
     * @param string $secret_key
     */
    public function __construct(string $site_key, string $secret_key)
    {
        $this->site_key = $site_key;
        $this->secret_key = $secret_key;

    }

    /**
     * Get ArCaptcha Script
     * @return string
     */
    public function getScript(): string
    {
        return sprintf('<script src="%s" async defer></script>', $this->script_url);
    }

    /**
     * Get Arcaptcha Widget
     * @return string
     */
    public function getWidget(): string
    {
        return sprintf('<div class="arcaptcha" data-site-key="%s"></div>', $this->site_key);
    }

    /**
     * Verify Captcha challenge id
     * @param string $challenge_id
     * @return bool
     */
    public function verify(string $challenge_id): bool
    {
        try {
            $response = $this->post($this->api_base_uri . 'verify', ['challenge_id' => $challenge_id, 'site_key' => $this->site_key, 'secret_key' => $this->secret_key]);
        } catch (Exception $e) {
            return false;
        }
        return $response['success'] ?? false;
    }

    private function post($url, $body)
    {
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode($body),
                'header'  => "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
            )
        );

        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        return json_decode($result, true);

    }
}
