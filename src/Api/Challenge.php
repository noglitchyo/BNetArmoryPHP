<?php
class Challenge extends API
{
	public $maxTimestamp;

	public function rehydrate ( array $data )
	{
		$data = array_values($data);

		parent::rehydrate($data);
	}
}
