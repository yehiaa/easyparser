<?php

/**
 * test filter parser and lexer
 */
class QueryParserTest extends PHPUnit_Framework_TestCase
{
	public function testOrderBy()
	{
		$queryStringOrderBy = "@orderby=name,-surname";

		$qs =  $queryStringOrderBy ;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->orderBy();
		$this->assertInternalType("array", $result);
		$this->assertCount(2, $result);
		$sort = new yehiaHamid\easyParse\queries\Sort("","");

		$this->assertInstanceOf("yehiaHamid\\easyParse\\queries\\Sort", $result[0]);

		$this->assertTrue($result[0]->field == "name");
		$this->assertNull($result[0]->direction);

		$this->assertTrue($result[1]->field == "surname");
		$this->assertTrue($result[1]->direction == "desc");

	}

	public function testOrderByReturnsNull()
	{
		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->orderBy();

		$this->assertNull($result);
	}

	/**
	 * @expectedException Exception 
	 *
	 */
	public function testOrderByThrowExceptionWhenEmptyArguments()
	{
		$queryStringOrderBy = "@orderby=";

		$qs =  $queryStringOrderBy ;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->orderBy();
	}

	public function testOrderBySingleValue()
	{
		$queryStringOrderBy = "@orderby=name";

		$qs = $queryStringOrderBy ;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->orderBy();
		//var_dump($result);
		$this->assertInternalType("array", $result);
		$this->assertCount(1, $result);
		$this->assertInstanceOf("yehiaHamid\\easyParse\\queries\\Sort", $result[0]);

		$this->assertTrue($result[0]->field == "name");
	}

	public function testOrderBySingleValueWithDescOrder()
	{
		$queryStringOrderBy = "@orderby=-name";

		$qs = $queryStringOrderBy ;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->orderBy();
		//var_dump($result);

		$this->assertTrue($result[0]->field == "name");
		$this->assertTrue($result[0]->direction == "desc");
	}


	public function testFilter()
	{
		$queryStringFilters = "@filters=name eq 'what ever',surname ne 'filter term'";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->filters();

		$this->assertInternalType("array", $result);
		$this->assertCount(2, $result);
		$this->assertInstanceOf("yehiaHamid\\easyParse\\queries\\Filter", $result[0]);

		$this->assertTrue($result[0]->field == "name");
		$this->assertTrue($result[0]->operator == "eq");
		$this->assertTrue($result[0]->value == "what ever");

		$this->assertTrue($result[1]->field == "surname");
		$this->assertTrue($result[1]->operator == "ne");
		$this->assertTrue($result[1]->value == "filter term");
	}

	public function testFilterSingleValue()
	{
		$queryStringFilters = "@filters=name eq 'what ever'";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->filters();
		//var_dump($result);
		$this->assertInternalType("array", $result);
		$this->assertCount(1, $result);
		$this->assertInstanceOf("yehiaHamid\\easyParse\\queries\\Filter", $result[0]);

		$this->assertTrue($result[0]->field == "name");
		$this->assertTrue($result[0]->operator == "eq");
		$this->assertTrue($result[0]->value == "what ever");
	}

	public function testFilterSingleQuoteMixedWithDoubleQuotes()
	{
		$queryStringFilters = "@filters=name eq 'inside single quote',surname ne \"inside double quote\"";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->filters();
		//var_dump($result);
		$this->assertInternalType("array", $result);
		$this->assertCount(2, $result);
		$this->assertInstanceOf("yehiaHamid\\easyParse\\queries\\Filter", $result[0]);

		$this->assertTrue($result[0]->field == "name");
		$this->assertTrue($result[0]->operator == "eq");
		$this->assertTrue($result[0]->value == "inside single quote");

		$this->assertTrue($result[1]->field == "surname");
		$this->assertTrue($result[1]->operator == "ne");
		$this->assertTrue($result[1]->value == "inside double quote");
	}

	public function testFilterSingleQuoteContainsDoubleQuote()
	{
		$queryStringFilters = "@filters=name eq 'what\"s up',surname ne \"filter's term\"";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->filters();

		$this->assertInternalType("array", $result);
		$this->assertCount(2, $result);
		$this->assertInstanceOf("yehiaHamid\\easyParse\\queries\\Filter", $result[0]);

		$this->assertTrue($result[0]->field == "name");
		$this->assertTrue($result[0]->operator == "eq");
		$this->assertTrue($result[0]->value == "what\"s up");

		$this->assertTrue($result[1]->field == "surname");
		$this->assertTrue($result[1]->operator == "ne");
		$this->assertTrue($result[1]->value == "filter's term");
	}
	
	/**
	 * @expectedException Exception
	 *
	 */
	public function testFilterThrowExceptionEmptyValue()
	{
		$queryStringFilters = "@filters=";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$q->filters();
	}

	public function testFilterReturnsNullIfNoFilters()
	{
		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);

		$result = $q->filters();
		
		$this->assertNull($result);
	}

	/**
	 * @expectedException Exception
	 */
	public function testFilterThrowExceptionIfInvalidOperator()
	{
		$queryStringFilters = "@filters=name eqq 'what ever',surname ne 'what ever'";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$q->filters();
	}


	/**
	 * @expectedException Exception
	 */
	public function testFilterThrowExceptionIfNoOperator()
	{
		$queryStringFilters = "@filters=name 'what ever',surname ne 'what ever'";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$q->filters();
	}

	/**
	 * @expectedException Exception
	 */
	public function testFilterThrowExceptionIfNoField()
	{
		$queryStringFilters = "@filters= eq 'what ever',surname ne 'what ever'";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$q->filters();
	}

	/**
	 * @expectedException Exception
	 */
	public function testFilterThrowExceptionIfNoValue()
	{
		$queryStringFilters = "@filters= name eq ,surname ne 'what ever'";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$q->filters();
	}

	public function testFields()
	{
		$queryStringFilters = "@filters= name eq ,surname ne 'what ever'";

		$queryStringFields = "@fields=name,surname,code";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->fields();

		$this->assertInternalType("array", $result);
		$this->assertCount(3, $result);

		$this->assertContains("name", $result);
		$this->assertContains("surname", $result);
		$this->assertContains("code", $result);

	}


	public function testFieldsReturnsNullIfNoFields()
	{
		$queryStringFilters = "@filters= name eq ,surname ne 'what ever'";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFilters . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);
		$result = $q->fields();

		$this->assertNull($result);

	}

	/**
	 * @expectedException Exception
	 */
	public function testFieldsThrowExceptionIfNoValue()
	{
		$queryStringFields = "@fields=";

		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $queryStringFields . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);

		$result = $q->fields();

	}

	public function testPagination()
	{

		$pagination = "@perpage=10&@offset=15&@limit=20";
		$queryStringOrderBy = "@orderby=name,-surname";

		$queryStringEmbed = "@embed=patientProcedures(@fields=name,code)(@filters=nameembed eq 'what ever'),mobiles(@orderby=sortFieldOne)";

		$qs = $pagination . "&" . $queryStringOrderBy . "&" . $queryStringEmbed;

		$q = new yehiaHamid\easyParse\QueryStringParser($qs);

		$perPage = $q->perPage();
		$offset = $q->offset();
		$limit = $q->limit();
		
		$this->assertEquals(10, $perPage);
		$this->assertEquals(15, $offset);
		$this->assertEquals(20, $limit);
		
	}

}
