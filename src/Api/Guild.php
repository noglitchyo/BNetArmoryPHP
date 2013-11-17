<?php
namespace Api;

class Guild extends \ApiMapper
{
    protected $fields = array('members', 'achievements', 'news', 'challenge');

    public function __construct($name, $realm)
    {
        $this->name     = $name;
        $this->realm    = $realm;
    }

    public function getUrl()
    {
        return 'guild/'.$this->realm.'/'.$this->name;
    }

    public function getMembers()
    {
        return $this->members; 
    }

    public function getAchievements()
    {
        return;
    }

    public function getNews()
    {
        return;
    }

    public function getChallenge()
    {
        return;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setAchievements()
    {
        return;
    }

    public function setNews($news)
    {
        foreach($news as &$value)
            $value = $this->push($value);

        return $news; 
    }

    public function setMembers($members)
    {
        foreach($members as &$value)
            $value = $this->push($value);

        return $members;
    }
}