<?php
/**
 * @todo Error handling from BNet
 */
class Request
{
    /**
     * API requested
     * @var \ApiMapper
     */
    protected $api = null;

    /**
     * Armory instance
     * @var \Armory
     */
    protected $armory;

    /**
     * Boolean to set @ true for let trailing slash to the end of the request
     * @var bool
     */
    protected $trailingSlash = false;

    /**
     * Constructer
     * @param \ApiMapper $api API to working with
     * @param \Armory $armory
     */
    public function __construct(ApiMapper $api, Armory $armory)
    {
        $this->api           = $api;
        $this->armory        = $armory;
    }

    /**
     * Return adequates headers for the request and authentificate the application.
     *
     * @access private
     * @return resource A stream context resource.
     */
    private function getHeaders()
    {
        $options = array(
            'http' => array(
                'method'        => 'GET',
                'ignore_errors' => 'true'
            )
        );

        if ($this->armory->privateKey) {
            $date           = date('D d M Y G:i:s T', time());
            $stringToSign   = 'GET\n' . $date . '\n' . $this->getUrl() . '\n';
            $signature      = base64_encode(hash_hmac('sha1', $stringToSign, utf8_encode($this->armory->getPrivateKey())));

            $options['http']['header'] = "Authorization: BNET".$this->armory->publicKey.":".$signature."\r\n";
        }

        return stream_context_create($options);
    }

    /**
     * Execute the request to remote server (Blizzard)
     *
     * @return string (JSON string)
     */
    public function execute()
    {
        if (function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->getUrl());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $content = curl_exec($ch);
            curl_close($ch);
        }
        else
            $content = file_get_contents($this->getUrl(), false, $this->getHeaders());

        if ( ! $content)
            throw new Exception('Error during requesting server', 400);

        return $content;
    }

    /**
     * Return the remote URI to request for get data from API
     *
     * @access private
     * @return string
     */ 
    private function getUrl()
    {
        $explodedUrl = explode('/', $this->api->getUrl());

        array_walk($explodedUrl, function(&$value, $key) {
            $value = urlencode($value);
        });

        $url = $this->armory->region.'.'.$this->armory->host.'/'.$this->armory->api.'/'.implode('/', $explodedUrl);

        if ($this->api->needTrailingSlash()) $url .= '/';

        if ( ! is_array($this->api->getFields()))
            throw new Exception('ApiMapper::getFields have to return an array.');

        $arrayQueryString = array(
            'locale' => $this->armory->locale,
            'fields' => implode(',',$this->api->getFields())
        );

        $url .= '?'.str_replace('+', '%20', http_build_query($arrayQueryString));
        echo $url;

        // Authentificated request required SSL protocol
        return $this->armory->privateKey ? 'https://' . $url : 'http://' . $url;
    }
}