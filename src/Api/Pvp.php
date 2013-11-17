<?php
/**
 * Class PvP
 *
 * Represent PvP APIs.
 *
 * This API has been separed in 2 class for distinct arena team and
 * teams / ladder informations.
 *
 * PVP APIs currently provide arena team and ladder information.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#pvp-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class Pvp extends API
{
	/**
	 * @access	protected
	 * @var	string
	 */
	protected $urlPattern = 'api.status.fields';

	/**
	 * Optional Query String Parameters
	 *
	 * page	: Which page of results to return (defaults to 1)
	 * size	: How many results to return per page (defaults to 50)
	 * asc	: Whether to return the results in ascending order.
	 * 		  Defaults to "true", accepts "true" or "false"
	 *
	 * @var array
	 * @access	protected
	 */
	protected $optionalFields = array(
			'page',
			'size',
			'asc'
	);

	/**
	 * Rated Battleground Ladder API
	 *
	 * The Rated Battleground Ladder API provides ladder information for a region.
	 *
	 * @param	string	$ladder
	 * @access	public
	 * @return	PvP
	*/
	public function getRatedBg ( $ladder )
	{
		return $this->get('ratedbg', $ladder);
	}

	/**
	 * Arena Ladder API
	 *
	 * The Arena Team Ladder API provides arena ladder information for a battlegroup.
	 *
	 * @param	string	$ladder
	 * @param	string	$teamsize
	 * @access	public
	 * @return	PvP
	 */
	public function getArenaLadder ( $ladder , $teamsize )
	{
		return $this->get('arena', array($ladder, $teamsize));
	}
}