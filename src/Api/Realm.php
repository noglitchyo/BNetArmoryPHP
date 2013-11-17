<?php
/**
 * Class Realm
 *
 * Represent realm APIs.
 *
 * The realm status API allows developers to retrieve realm status information.
 * This information is limited to whether or not the realm is up, the type and
 * state of the realm, the current population, and the status of the two world
 * pvp zones.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#realm-status-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class Realm extends API
{
	/**
	 * @access	private
	 * @var	string
	 */
	protected $urlPattern = 'api.status';
}
