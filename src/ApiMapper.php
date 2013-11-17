<?php
/**
 * Abstract class for mapping Battle.NET API
 * 
 */
abstract class ApiMapper
{
    /**
     * Timestamp where the API was put into cached
     * @var int
     */
    protected $fromCache = 0;

    /**
     * Boolean for indicate if specified API require a trailing slash in the request URI
     * Because some API requires trailing slash. I dont know why, it's bullshit!
     * @var bool
     */
    protected $trailingSlash = false;

    /**
     * API's url can be really different one to each others
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
     * Map data from an array to the ApiMapper as his properties
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