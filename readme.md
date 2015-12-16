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



#Usage 

'''php
$queryStringFilters = "@filters=name eq 'what ever',surname ne 'what ever'";

$queryStringFields = "@fields=name,surname,code";

$queryStringOrderBy = "@orderby=name,-surname";

//embed can contains fields,filters,sort and paging 
$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

$queryString = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

//in real app you would use $_SERVER['QUERY_STRING'];
$parser = new yehiaHamid\easyParse\QueryStringParser($queryString);

'''
