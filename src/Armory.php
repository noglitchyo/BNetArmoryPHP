<?php
/**
 * Armory class is the one to call for working with BNetArmory
 * This new revision work with Composer and is better structured.
 * 
 * @author Maxime Elomari <maxime.elomari@gmail.com>
 * @version 0.2
 * @see https://github.com/Skw33d/BNetArmoryPHP/tree/master/API
 */
class Armory
{
    /**
     * Host
     * @var string
     */
    public $host = 'battle.net';

    /**
     * @var string
     */
    public $region = null;

    /**
     * API to working with
     * @var string
     */
    public $api = 'api/wow';

    /**
     * Set to true for cache data
     * @var bool
     */
    public $cacheEnabled = true;

    /**
     * Path to the cache directory
     * @var string
     */
    public $cacheDirectory = 'cache';

    /**
     * Time to keep data in cache before make a refresh from Blizzard server
     * @var int
     */
    public $cacheTime = 1600;

    /**
     * Public key
     * @var string
     */
    public $publicKey = null;

    /**
     * Private key
     * @var string
     */
    public $privateKey = null;

    /**
     * Langage into data must be returned
     * @var string
     * @see https://github.com/Blizzard/api-wow-docs#region-host-list
     */
    public $locale;

    /**
     * Application directory who ontains all mapped API in PHP.
     * @var string
     */
    public $apiDirectory = 'Api';

    /**
     * Instance of Cache
     * @var \Cache
     */
    public $cache;

    /**
     * Boolean @true, the next request will be get from the server and not from cache even if activated
     * @var bool
     */
    private $refreshCacheNextRequest = false;

    /**
     * Instance a new Armory object for a region with specified locale 
     *
     * @param string $region A valid region : EU, US, TW... (@see)
     * @param string $locale A valid locale (@see)
     * @see https://github.com/Blizzard/api-wow-docs#region-host-list
     */
    public function __construct($region, $locale)
    {
        $this->region = strtolower($region);
        $this->locale = $locale;
        $this->cache  = new Cache($this);
    }

    /**
     * Call the $name API stored in $this->apiDirectory. These API's are mappers representing the API.
     *
     * @param string $name A valid call must begin by get followed by the name of the mapped object
     * @param array $argsForMappedApi Function's arguments passed to the constructor of the class
     * @return \ApiMapper 
     */
    public function __call($name, array $argsForMappedApi = array())
    {
        if ( ! preg_match('/^get([a-zA-Z]+)$/', $name, $matches))
            throw new Exception('Nothing to get.');

        $apiClass   = 'Api\\'.$matches[1];

        if ( ! file_exists(__DIR__.DIRECTORY_SEPARATOR.$this->apiDirectory.DIRECTORY_SEPARATOR.$matches[1].'.php'))
            throw new Exception('No mapped API '.$matches[1].' in '.$this->apiDirectory, 1);

        $reflector  = new ReflectionClass($apiClass);
        $api        = $reflector->newInstanceArgs($argsForMappedApi);

        if ( ! $this->cacheEnabled || $this->cache->isCacheExpired($api) || $this->refreshCacheNextRequest) {

            $request  = new Request($api, $this);
            $response = json_decode($request->execute(), true);

            if (isset($response['status']) && isset($response['reason']))
                throw new Exception($response['reason'], 404);

            $api->map($response);

            if ($this->cacheEnabled)
                $this->cache->put($api);
            
            $this->refreshCacheNextRequest = false;
        }
        else
            $api = $this->cache->get($api);
        
        return $api;     
    }

    /**
     * The next request will be get from server even if cache is activated
     * @return $this
     */
    public function refreshNext()
    {
        $this->refreshCacheNextRequest = true;

        return $this;
    }
}