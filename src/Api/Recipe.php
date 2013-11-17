<?php
/**
 * Class Recipe
 *
 * Represent the recipe APIs.
 * The recipe API provides basic recipe information.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#recipe-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class Recipe extends API
{
	/**
	 * @access	private
	 * @var	string
	 */
	protected $urlPattern = 'api.status';
}