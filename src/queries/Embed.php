<?php namespace yehiaHamid\easyParse\queries;

/**
 * Embed query class
 */
class Embed
{
	public $resource;
	public $filter;
	public $fields;
	public $orderBy;
	/**
	 * 
	 */
	public function __construct($resource, $filter=null, $fields=null, $orderBy=null)
	{
		$this->resource = $resource ;
		$this->filter = $filter ;
		$this->fields = $fields ;
		$this->orderBy = $orderBy ;
	}
}
