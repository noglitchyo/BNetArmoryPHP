<?php
/**
 * Class Armory
 *
 * Armory class is the one to call for working with BNetArmory
 * This new revision work with Composer and is better structured.
 * All the configuration can be controlled by this class.
 * 
 * @author Maxime Elomari <maxime.elomari@gmail.com>
 * @version 0.2
 * @see https://github.com/Skw33d/BNetArmoryPHP/tree/master/API
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright 2013 Maxime ELOMARI
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
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

        $api = new $apiClass($this);
        $api->fetch($argsForMappedApi);

        if ( ! $this->cacheEnabled || ($this->cache->isCacheExpired($api) && $this->cacheEnabled) || $this->refreshCacheNextRequest) {
            
            $request  = new Request($api, $this);
            $response = json_decode($request->execute(), true);

            if (isset($response['status']) && isset($response['reason']))
                throw new Exception($response['reason'], 404);

            $this->refreshCacheNextRequest = false;
            
            $api->map($response);

            if ($this->cacheEnabled)
                $this->cache->put($api);
            
            
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