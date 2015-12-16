#easyparse 
easy query is a simple query language for rest api php 

it is framework agnostic

you can parse your query string to 
filter, 
sort, 
get certain fields
and embed resources

#installation 
using composer 
composer require yehiahamid/easyparse


#Usage 

```php

//values must be inside single quotes ''
$queryStringFilters = "@filters=name eq 'what ever',surname ne 'what ever'";

$queryStringFields = "@fields=name,surname,code";

//name is assc , surname desc
$queryStringOrderBy = "@orderby=name,-surname";

//embed can contains fields,filters,sort and paging 
$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

$queryString = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

//in real app you would use $_SERVER['QUERY_STRING'];
$parser = new yehiaHamid\easyParse\QueryStringParser($queryString);

$parser->fields(); //return array of required fields | null if empty 

$parser->embed(); //return array of embed each object contains filters , sort , fields | empty array 

$parser->orderBy();

$parser->filters();

$parser->offset();
$parser->limit();
$parser->perPage();
$parser->page();
$parser->search();

```

#available filtering operator 
in Query string | in result object 
--------------- | -----------------
eq    			| =
ne    			| !=
gt	  			| >
lt	  			| <
le    			| <=
ge	  			| >=


#fixes

to do(s) 
- count 
- default values 
- configuration 
- convert operators to usable 
- use default 
- new operators le less or equal,ge greater or equal

