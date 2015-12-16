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

    public function __construct($queryString){
        $this->queryString = $this->getQueryStringAsArray($queryString);
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
        return explode($separator,$string);
    }

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

    public function embed()
    {
        if(isset($this->queryString["@embed"]))
        {
			$parsed = $this->getEmbedData($this->queryString["@embed"]);
			return $this->getEmbedQueries($parsed);
        }

		return null;
    }

	public function offset()
	{
        if(isset($this->queryString["@offset"]))
        {
			return $this->queryString["@offset"];
        }

		return null;		
	}

	public function limit()
	{
        if(isset($this->queryString["@limit"]))
        {
			return $this->queryString["@limit"];
        }

		return null;		
	}

	public function perPage()
	{
        if(isset($this->queryString["@perpage"]))
        {
			return $this->queryString["@perpage"];
        }

		return null;		
	}

	public function page()
	{
        if(isset($this->queryString["@page"]))
        {
			return $this->queryString["@page"];
        }

		return null;		
	}
	
    public function orderBy()
    {
        if (isset($this->queryString["@orderby"])) {
			$parsed = $this->getOrderBysData($this->queryString["@orderby"]);
			return $this->getOrderBysQueries($parsed);
        }

		return null;
    }

    public function filters()
    {
        if(isset($this->queryString["@filters"]))
        {
			$parsedFilters = $this->getFiltersData($this->queryString["@filters"]);
			return 	$this->getFilterQueries($parsedFilters);
        }

		return null;
    }

    public function search()
    {
        if(isset($this->queryString["@search"]))
        {
            return $this->queryString["@search"];
        }

		return null;
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






