<?php
namespace Api;

/**
 * Class Achievement
 *
 * Represent achievement APIs.
 *
 * This provides data about an individual achievement.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#achievement-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.2
 */
class Achievement extends \ApiMapper
{
    public function fetch(array $args)
    {
        $this->id = $args[0];
    }
    
    public function getUrl()
    {
        return 'achievement/'.$this->id;
    }

    public function getFields()
    {
        return array();
    }
}