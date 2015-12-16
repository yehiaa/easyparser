<?php namespace yehiaHamid\easyParse\queries;
/**
 * this is Filter Query class
 */
class Filter
{
	public $field ;
	public $operator;
	public $value ;
	/**
	 * 
	 */
	public function __construct($field, $operator, $value)
	{
		$this->field = $field ;
		$this->operator = $operator ;
		$this->value = $value ;
	}
}
