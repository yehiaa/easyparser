<?php

include 'vendor/autoload.php';

function isCLI()
{
    return (php_sapi_name() === 'cli');
}

function hr($value=90)
{
	if(isCLI())
	{
		echo "\n";
		foreach (range(0, $value) as $val) { 
			echo "_";
		}
		echo "\n";
	}else
	{
		echo "<hr>";
		echo "<pre>";
	}
}


$queryStringFilters = "@filters=name eq 'what ever',surname ne 'what ever'";

$queryStringFields = "@fields=name,surname,code";

$queryStringOrderBy = "@orderby=name,-surname";

$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

$queryString = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

if (isset($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
}

if (isset($argv[1])) {
    $queryString = $argv[1];
}

hr();
echo urldecode($queryString) ;
hr();

$parser = new \yehiaHamid\easyParse\QueryStringParser($queryString);
print_r($parser->filters());
hr();
print_r($parser->fields());
hr();
print_r($parser->orderBy());
hr();
print_r($parser->embed());

// $input = ["fieldone eq 'value one'"];
$input = "name eq 'what ever', fieldname1_a ne \"that's term\" , 1any ne '10/12/122'" ;
hr();
// var_dump($result);
hr();
// $result = FilterParser::run($result);
hr();
// var_dump($result);

hr();
// var_dump($filters);
