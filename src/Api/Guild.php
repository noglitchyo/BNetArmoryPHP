<?php
/**
 * Class Guild
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
namespace Api;

class Guild extends \ApiMapper
{
    protected $fields = array('members', 'achievements', 'news', 'challenge');

    public function fetch(array $args)
    {
        $this->name     = $args[0];
        $this->realm    = $args[1];
    }

    public function getUrl()
    {
        return 'guild/'.$this->realm.'/'.$this->name;
    }

    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Callback called while data mapping when the key achievements is retrieved in data array
     * @param array $achievements
     * @return array
     */
    public function setAchievements(array $achievements)
    {
        foreach ($achievements['achievementsCompleted'] as &$achievement)
            $achievement = $this->armory->getAchievement($achievement);

        return $achievements;
    } 

    /**
     * Callback called while data mapping when the key news is retrieved in data array
     * @param array $news
     * @return array
     */
    public function setNews(array $news)
    {
        foreach($news as &$value)
            $value = $this->push($value);

        return $news; 
    }

    public function setChallenges(array $challenges)
    {
        foreach ($challenges as &$challenge)
            $challenge = $this->push($challenge);

        return $challenges;
    }

    /**
     * Callback called while data mapping when the key members is retrieved in data array
     * @param array $members 
     * @return array
     */
    public function setMembers(array $members)
    {
        foreach($members as &$value)
            $value = $this->push($value);

        return $members;
    }
}