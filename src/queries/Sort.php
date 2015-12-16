<?php namespace yehiaHamid\easyParse\queries ;

/**
 * Sort Query class
 */
class Sort
{
	public $field ;
	public $direction ;

	/**
	 * 
	 */
	public function __construct($field, $direction)
	{
		$this->field = $field ;
		$this->direction = $direction ;		
	}
}
