<?php
/**
 * Abstract class ApiMapper.
 * This class describe features required for implementing a new API mapper.
 * ApiMapper are PHP objects representing each API services purposed by Battle.net
 * New services can be quickly implemented by extend this class.
 * 
 * @todo Error handling
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
abstract class ApiMapper
{
    /**
     * Timestamp where the API was put into cached
     * @var int
     * @access protected
     */
    protected $fromCache = 0;

    /**
     * Boolean for indicate if specified API require a trailing slash in the request URI
     * Because some API requires trailing slash. I dont know why, it's bullshit!
     * @var bool
     * @access protected
     */
    protected $trailingSlash = false;

    /**
     * API's url can be really different one to each others.
     * You can find how to build your url by taking the pattern of the API service to implement below.
     * @see http://blizzard.github.io/api-wow-docs/
     * Don't take care about the "api/wow", Request class do this for you. URL begin by the service's name.
     * 
     * @access public
     * @return string
     */
    abstract public function getUrl();

    /**
     * Had to return an array with each fields to get
     * @access public
     * @return array
     */
    abstract public function getFields();

    /**
     * Map data from an array to the ApiMapper as his properties.
     * Setter can be define in the class by implementing method following this pattern : setPropertieName
     * It's useful when you get data like members from Guild API who is a multidimensionnal array with ApiMapper
     * as ApiMapper\Character. Don't forget to use them for implement new features.
     * @param array $data
     */
    final public function map(array $data)
    {
        foreach ($data as $key => $value) {
            if (method_exists($this, $method = 'set'.ucwords($key)))
                $this->$key = $this->$method($value);
            else
                $this->$key = $this->push($value);
        }
    }

    /**
     * Push data and create dependencies with other mapped API's
     * @param mixed $data
     * @access protected 
     * @return mixed
     */
    protected function push($data)
    {
        $pushed = null;

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) && class_exists($class= '\Api\\'.ucwords($key))) {
                    $pushed[$key] = new $class();
                    $pushed[$key]->map($value);
                }
                else
                    $pushed[$key] = $value;
            }
        }
        else
            $pushed = $data;

        return $pushed;
    }

    /**
     * Return true if the version is getting from cache
     * 
     * @return bool
     */
    public function isFromCache()
    {
        return (bool) $this->fromCache;
    }

    /**
     * @see self::$trailingSlash
     */
    public function needTrailingSlash()
    {
        return (bool) $this->trailingSlash;
    }

    public function __wakeup()
    {
        $this->fromCache = true;
    }
}