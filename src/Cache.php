<?php
/**
 * Class Cache
 *
 * Cache system for BNetArmoryPHP
 * Serialize ApiMapper and cache them in specified folder by Armory::$cacheDirectory
 * Files are base64_encode for no problems with filename.
 * 
 * @author Maxime Elomari <maxime.elomari@gmail.com>
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
     * Cache is stored in specified folder by Armory::$cacheDirectory.
     * The next of the path is defined by <Region>/<Locale>/<Api>/<Realm*>/<base64_encode(Identifier)>
     * @param \ApiMapper $api A mapped object
     * @access private
     * @return \ApiMapper|bool
     */
    private function getPath(ApiMapper $api)
    {
        $urlExploded = explode('/', $api->getUrl($this->armory));  
        $index = count($urlExploded) - 1;
        $urlExploded[$index] = base64_encode($urlExploded[$index]);

        return  $this->armory->cacheDirectory.DIRECTORY_SEPARATOR.
                $this->armory->region.DIRECTORY_SEPARATOR.
                $this->armory->locale.DIRECTORY_SEPARATOR.
                str_replace('/', DIRECTORY_SEPARATOR, implode('/', $urlExploded));
    }
}