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


$queryStringFilters = "@filters=id eq 12 ,id eq anyWordsWithoutspacesOrSpecialChars , name eq 'what ever',surname ne 'what ever',x le 'htest',Y ge \"greater's or less \",xx le \"10/10/2015 10:10:00 am pm\"";
$queryStringCustom = "@anyThing=this is any thing value& @customwithQuotes='this is any shit in quotes'";

$queryStringFields = "@fields=name,surname,code";

$queryStringOrderBy = "@orderby=name,-surname";
//$queryStringOrderBy = "";

$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

$queryString = $queryStringFilters . "&" .
    $queryStringFields . "&" .
    $queryStringEmbed  . "&" .
//    $queryStringOrderBy . "&" .
    $queryStringCustom;

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

print_r($parser->get("anyThing"));
hr();
print_r($parser->get("customwithQuotes"));
hr();
print_r($parser->filters());
hr();
print_r($parser->fields());
hr();
print_r($parser->orderBy("firsrField,-secondField"));
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
foreach ($parser->filters() as $filter) {
	hr();
	print_r($filter->getOperator());
}
hr();
hr();
// var_dump($filters);
