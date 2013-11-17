<?php
/**
 * Class ArenaTeam
 *
 * TeamSize = "2v2" | "3v3" | "5v5"
 *
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class ArenaTeam extends API
{
	/**
	 * URL Api haven't same name of the class, so we overload getApi method for
	 * return specific name.
	 *
	 * @see API::getApi()
	 */
	protected function getApi ( )
	{
		return DIRECTORY_SEPARATOR . 'arena';
	}
}