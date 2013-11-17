<?php
class Cache
{
    /**
     * Instance of Armory
     * @var \Armory
     */
    private $armory;

    /**
     * Constructer
     * @param \Armory $armory
     */
    public function __construct(Armory $armory)
    {
        $this->armory = $armory;
    }

    /**
     * Put the specified object in cache
     * @param \ApiMapper $api A mapped object
     * @return bool
     */
    public function put(ApiMapper $api)
    {
        if ( ! file_exists(dirname($this->getPath($api))))
            mkdir(dirname($this->getPath($api)), 0777, true);

        return (bool) file_put_contents($this->getPath($api), serialize($api), LOCK_EX);
    }

    /**
     * Get the specified object from cache
     * @param \ApiMapper $api A mapped object
     * @return \ApiMapper|bool
     */
    public function get(ApiMapper $api)
    {
        if ( ! file_exists($this->getPath($api)))
            return false;

        return unserialize(file_get_contents($this->getPath($api)));
    }

    /**
     * Return true if cache has expired, false otherwise
     * @param \ApiMapper $api A mapped object
     * @return \ApiMapper|bool
     */
    public function isCacheExpired(ApiMapper $api)
    {
        if ( ! file_exists($this->getPath($api)))
            return true;

        return (time() - filemtime($this->getPath($api))) >= $this->armory->cacheTime ? true : false;
    }

    /**
     * Return local path to cached object file
     * @param \ApiMapper $api A mapped object
     * @access private
     * @return \ApiMapper|bool
     */
    private function getPath(ApiMapper $api)
    {
        $urlExploded = explode('/', $api->getUrl($this->armory));  
        $index = count($urlExploded) - 1;
        $urlExploded[$index] = base64_encode($urlExploded[$index]);

        return $this->armory->cacheDirectory.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, implode('/', $urlExploded));
    }
}