<?php
namespace Api;

class Character extends \ApiMapper
{
    public function __construct($name = null, $realm = null)
    {
        $this->name = $name;
        $this->realm = $realm;
    }

    public function getUrl()
    {
        return 'character/'.$this->realm.'/'.$this->name;
    }

    public function getFields()
    {
        return array();
    }
}