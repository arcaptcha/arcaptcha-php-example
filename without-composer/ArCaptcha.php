<?php

class ArCaptcha
{
/**
 * Api Base Uri
 * @var string
 */
    protected $api_base_uri = 'https://arcaptcha.co/2/';

    /**
     * Script Url
     * @var string
     */
    protected $script_url = 'https://widget.arcaptcha.co/2/api.js';

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
     * Widget Color
     * @var string
     */
    protected $color;

    /**
     * Widget Language
     * @var string
     */
    protected $lang;

    /**
     * Widget size (invisible or normal)
     * @var string
     */
    protected $size;

    /**
     * Callback function name after challenge is solved
     * @var string
     */
    protected $callback;

    /**
     * Http Adapter
     * @var Http
     */
    protected $http;

    /**
     * ArCaptcha Constructor
     * @param string $site_key
     * @param string $secret_key
     * @param array $options
     */
    public function __construct(string $site_key, string $secret_key, array $options = [])
    {
        $this->site_key = $site_key;
        $this->secret_key = $secret_key;
        $this->color = $options['color'] ?? 'normal';
        $this->lang = $options['lang'] ?? 'fa';
        $this->size = $options['size'] ?? 'normal';
        $this->theme = $options['theme'] ?? 'light';
        $this->callback = $options['callback'] ?? '';
    }

    /**
     * Verify Captcha challenge id
     * @param string $challenge_id
     * @return bool
     */
    public function verify(string $challenge_id): bool
    {
        try {
            $response = $this->post($this->api_base_uri . 'siteverify', ['response' => $challenge_id, 'sitekey' => $this->site_key, 'secret' => $this->secret_key]);
        } catch (Exception $e) {
            return false;
        }
        return $response['success'] ?? false;
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
        return sprintf(
            '<div class="arcaptcha" data-site-key="%s" data-color="%s" data-lang="%s" data-size="%s" data-theme="%s" data-callback="%s"></div>',
            $this->site_key,
            $this->color,
            $this->lang,
            $this->size,
            $this->theme,
            $this->callback
        );

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
