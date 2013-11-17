<?php
/**
 * Class BattlePet
 *
 * Represent BattlePet APIs
 *
 * This provides data about a individual battle pet ability ID.
 * We do not provide the tooltip for the ability yet.
 * We are working on a better way to provide this since it depends on your pet's species,
 * level and quality rolls.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#battlepet-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class BattlePet extends API
{
	/**
	 * @access	private
	 * @var	string
	 */
	protected $urlPattern = 'api.status.fields';

	/**
	 * Optional Query String Parameters
	 *
	 * @var array
	 * @access	protected
	 */
	protected $optionalFields = array(
			'level' => 1, // The Pet's level
			'breedId' => 3, // The Pet's breed (can be retrieved from the character profile api)
			'qualityId' => 1 // The Pet's quality (can be retrieved from the character profile api)
	);
}