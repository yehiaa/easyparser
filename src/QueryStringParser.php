<?php namespace yehiaHamid\easyParse ;
/**
 * File Name QueryStringParser.php.
 * Author: yehia abdul hamid
 */
class QueryStringParser {

    /**
     * @var
     */
    private $queryString;

    private $reservedKeys;


    /**
     * @param null $queryString
     */
    public function __construct($queryString=null){
    	$queryString = ($queryString) ? $queryString : $_SERVER['QUERY_STRING'];
        $this->queryString = $this->getQueryStringAsArray($queryString);

        $this->reservedKeys = array(
        	"field", "fields",
            "embed", "embeds",
            "filter", "filters",
            "orderby", "orderBy",
            "direction", "search"
        );
    }

    private function checkReservedKeys($key)
    {
    	return in_array($key, $this->reservedKeys);
    }

    private function getQueryStringAsArray($queryString){
        $result = $queryString;
        if(! is_array($queryString) )
        {
            $result = null ;
            parse_str($queryString, $result) ;
        }

        return $result;
    }

    private function stringToArray($string, $separator=","){
        return explode($separator, $string);
    }

    /**
     * get custom key (param) you can specify a default value if key not found
     * @param string $key
     * @param string $default set default value if key not found
     * @return string|null value of key if found or null or default value if set
     */
    public function get($key, $default=null)
    {
    	if($this->checkReservedKeys($key) ) return null ;

    	$sparam = "@".trim($key);

        if(isset($this->queryString[$sparam]) )
        {
        	return $this->queryString[$sparam] ;
        }

		return $default;
    }

    /**
     * get fields param
     * @return array|null array or fields or null of not found
     * @throws \Exception if fields has no content
     */
    public function fields()
    {
        if(isset($this->queryString["@fields"]))
        {
			if (empty($this->queryString["@fields"])) {
				throw new \Exception("empty fields");	
			}

            return $this->stringToArray($this->queryString["@fields"]);
        }

		return null;
    }

    /**
     * get @embed param if found the result is array of embed objects
     * @return array|null array of embed objects or null if not found
     */
    public function embed()
    {
        if(isset($this->queryString["@embed"]))
        {
			$parsed = $this->getEmbedData($this->queryString["@embed"]);
			return $this->getEmbedQueries($parsed);
        }

		return null;
    }

    /**
     * get @offset param if found
     * @param string $default optional default value if key not found
     * @return string|null value of offset if found or null or default value if set
     */
    public function offset($default=null)
	{
        return isset($this->queryString["@offset"]) ? $this->queryString["@offset"] : $default;
	}

    /**
     * get @limit param if found
     * @param string $default optional default value if key not found
     * @return string|null value of limit if found or null or default value if set
     */
    public function limit($default=null)
	{
        return isset($this->queryString["@limit"]) ? $this->queryString["@limit"] : $default;
	}

    /**
     * get @perpage param if found
     * @param string $default optional default value if key not found
     * @return string|null value of perpage if found or null or default value if set
     */
	public function perPage($default=null)
	{
        return isset($this->queryString["@perpage"]) ? $this->queryString["@perpage"] : $default;
	}

    /**
     * get @page param if found
     * @param string $default optional default value if key not found
     * @return string|null value of page if found or null or default value if set
     */
	public function page($default=null)
	{
        return isset($this->queryString["@page"]) ? $this->queryString["@page"] : $default ;
	}

    /**
     * get @orderby param if found
     * @param string $orderBys optional default value if key not found <p> "fieldName, -fieldName2" </p>
     * @return array|null array of orderBy object or null of not found or array of defaults if set
     */
    public function orderBy($orderBy=null)
    {
        $orderBysStr = null ;
        $orderBysStr = (isset($this->queryString["@orderby"])) ? $this->queryString["@orderby"] : $orderBy;

        if($orderBysStr)
        {
            $parsed = $this->getOrderBysData($orderBysStr);
            return $this->getOrderBysQueries($parsed);
        }

		return null;
    }

    /**
     * get @filters param if found
     * @return array|null get array of filter object if found or null
     */
    public function filters()
    {
        if(isset($this->queryString["@filters"]))
        {
			$parsedFilters = $this->getFiltersData($this->queryString["@filters"]);
			return 	$this->getFilterQueries($parsedFilters);
        }

		return null;
    }

    /**
     * get @search param if found
     * @return string|null value of search if found or null
     */
    public function search()
    {
        return (isset($this->queryString["@search"])) ? $this->queryString["@search"] : null;
	}
	
	private function getFilterQueries(array $filtersArray)
	{
		$filterQueries = [];

		foreach ($filtersArray as $filter) {
			$filterQueries [] = new queries\Filter($filter["field"], $filter["operator"], $filter["value"]);
		}
		return $filterQueries ;
	}
	
	private function getFiltersData($filtersString)
	{
		$data = parsers\filter\Lexer::run($filtersString);
		return parsers\filter\Parser::run($data);
	}

	private function getOrderBysData($orderBysString)
	{
		$data = parsers\sort\Lexer::run($orderBysString);
		return parsers\sort\Parser::run($data);
	}

	private function getOrderBysQueries(array $orderBysArray)
	{
		$orderByQueries = [] ;
		foreach ($orderBysArray as $orderBy) {
			$orderByQueries [] = new queries\Sort($orderBy["field"], $orderBy["direction"]);
		}

		return $orderByQueries ;
	}

	private function getEmbedData($embedString)
	{
		$data = parsers\embed\Lexer::run($embedString);
		return parsers\embed\Parser::run($data);
	}


	private function getEmbedQueries(array $embedsArray)
	{
		$embedQueries = [] ;
		foreach ($embedsArray as $embed) {
			$filtersQueries = null ;
			$fields = null ;
			$sortQueries = null ;

			if (isset($embed["filters"])) {
				$data =	$this->getFiltersData($embed["filters"]);
				$filtersQueries = $this->getFilterQueries($data);
			}

			if (isset($embed["fields"])) {
				$fields = $this->stringToArray($embed["fields"]);	
			}

			if (isset($embed["orderby"])) {
				$data = $this->getOrderBysData($embed["orderby"]);
				$sortQueries = 	$this->getOrderBysQueries($data); 
			}

			$embedQuery = new queries\Embed($embed["resource"], $filtersQueries, $fields, $sortQueries );

			$embedQueries [] = $embedQuery;

		}			

		return $embedQueries ;
	}
}






