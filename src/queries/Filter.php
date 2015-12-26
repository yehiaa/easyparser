<?php namespace yehiaHamid\easyParse\queries;
/**
 * this is Filter Query class
 */
class Filter
{
	public $field ;
	public $operator;
	public $value ;
    private $operatorsMap ;
	/**
	 * 
	 */
	public function __construct($field, $operator, $value)
	{
		$this->field = $field ;
		$this->operator = $operator ;
		$this->value = $value ;

        $this->operatorsMap = array(
            "eq" => "=",
            "ne" => "!=",
            "lt" => "<",
            "gt" => ">",
            "like" => "like",
            "ilike" => "ilike",
            "le" => "<=",
            "ge" => ">=",
        );
	}

    /**
     * get usable operator instead of eq you get =
     * @return string usable operator
     */
    public function getOperator()
    {
        return $this->operatorsMap[$this->operator] ;
    }
}
