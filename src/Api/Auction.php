<?php
/**
 * Class Auction
 *
 * Represent auction APIs.
 *
 * Auction APIs currently provide rolling batches of data about current auctions.
 * Fetching auction dumps is a two step process that involves checking a per-realm
 * index file to determine if a recent dump has been generated and then fetching
 * the most recently generated dump file if necessary.
 *
 * @see	http://blizzard.github.com/api-wow-docs/#auction-api
 * @author	Maxime Elomari <maxime.elomari@gmail.com>
 * @version	0.1
 */
class Auction extends API
{
	/**
	 * Auction Data Status
	 *
	 * This API resource provides a per-realm list of recently generated
	 * auction house data dumps.
	 *
	 */
	public function getData ( )
	{
		$this->urlPattern = '';
	}
}