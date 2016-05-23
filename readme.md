#easyparse 
easy query is a simple query language for rest api php 

it is framework agnostic

you can parse your query string to 
filter, 
sort, 
get certain fields
and embed resources
[![Build Status](https://travis-ci.org/yehiaa/easyparser.svg?branch=master)]
#installation using composer 

composer require yehiahamid/easyparse


#Usage 

```php

//values must be inside single or double quotes if it contains white spaces or special chars 'what ever' | "what ever"
$queryStringFilters = "@filters=name eq 'what ever',surname ne 'what ever'";
// otherwise you can use use the value without quotes 
// ex : 

$queryStringFilters = "@filters=id eq 12";
$queryStringFilters = "@filters=id eq anyWordsWithoutspacesOrSpecialChars";

//fields
$queryStringFields = "@fields=name,surname,code";

//name is assc , surname desc
$queryStringOrderBy = "@orderby=name,-surname";

//embed can contains fields,filters,sort and paging 
$queryStringEmbed = "@embed=resourceOne(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

$queryString = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

//in real app you would use $_SERVER['QUERY_STRING'];
//this is optional param if not set it will use server query string 
$parser = new yehiaHamid\easyParse\QueryStringParser($queryString);

$parser->fields(); //return array of fields | null if empty

$parser->orderBy(); //return array of order by object | null if empty
//Or you can set default value
// use - before field for desc order

$parser->orderBy("fieldOne,-field");   

$filtersResult = $parser->filters(); 
//the filter object has three fields 
// the used query string in ex : "@filters=name eq 'what ever',surname ne 'what ever'"

$filtersResult[0]->field // name
$filtersResult[0]->operator // eq
$filtersResult[0]->getOperator() // "="
$filtersResult[0]->value // 'what ever'


$filtersResult[1]->field // surname
$filtersResult[1]->operator // ne
$filtersResult[1]->getOperator() // "!="
$filtersResult[1]->value // 'what ever'

//Or you can set default value for each of the following 
$parser->offset();
$parser->limit();
$parser->perPage();
$parser->page();

// this new function return the value of required key , the key shouldn't be in reserved key words
// reserved key words "field", "fields", "embed", "embeds", "filter", "filters", "orderby", "orderBy", "direction", "search"
$parser->get("key");
// ex: 

$qs = "@anthThing=the value";
$parser->get("anthThing"); //returns the value
// also you can pass second param for default value


$parser->search();


//embed 
$embedResult = $parser->embed(); //return array of embed each object contains filters , sort , fields | empty array 
// the used query string in ex : 
//"@embed=resourceOne(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)"
$embedResult[0]->resource // resourceOne
$embedResult[0]->fields // array of fields  [name,code]
$embedResult[0]->filters // array of filters or empty array 

$embedResult[1]->resource // mobiles
$embedResult[1]->orderBy // array of order by objects 

```

#available filtering operator 
in Query string | in result object 
--------------- | -----------------
eq    			| =
ne    			| !=
gt	  			| >
lt	  			| <
like  			| like
ilike  			| ilike
le    			| <= 
ge	  			| >= 


