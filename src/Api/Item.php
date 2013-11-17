<?php
/**
 * Class Item
 *
 * Represent item APIs
 *
 * The item API provides data about items and item sets.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#item-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class Item extends API
{
	/**
	 * @access	private
	 * @var	string
	 */
	protected $urlPattern = 'api.status.fields';

	/**
	 * Item Set
	 *
	 * The item set data provides the data for an item set.
	 *
	 * @param	string	$id
	 * @return	Item
	 */
	public function getSet ( $id )
	{
		return $this->get('set', $id);
	}
}