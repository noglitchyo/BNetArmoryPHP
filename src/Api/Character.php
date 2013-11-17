<?php
/**
 * Class Character
 *
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

class Character extends \ApiMapper
{
    public function fetch(array $args)
    {
        $this->name  = $args[0];
        $this->realm = $args[1];
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