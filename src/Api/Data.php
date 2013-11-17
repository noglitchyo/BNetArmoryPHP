<?php
/**
 * Class Data
 *
 * Represent the data APIs.
 * The data APIs provide information that can compliment profile information to
 * provide structure, definition and context.
 *
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class Data extends API
{
	protected $urlPattern = 'api.status.fields';

	public function getBattlegroups ( )
	{
		$this->urlTrailingSlash = true;
	}

	public function getCharacter ( $fields )
	{

	}

	public function getGuild ( $fields )
	{

	}

	public function getItemClasses ( )
	{

	}

	public function getTalents ( )
	{

	}

	public function getPetTypes ( )
	{

	}
}