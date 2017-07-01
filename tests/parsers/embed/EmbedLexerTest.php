<?php

/**
 * test filter parser and lexer
 */
class EmbedLexerTest extends \PHPUnit_Framework_TestCase
{
	public function testGetResources()
	{
		$string = "patient,patientMobile,customers" ;
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);
		//var_dump($lexerResult);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("patient" == $lexerResult[0]["match"]);

		$this->assertTrue("T_COMMA" == $lexerResult[1]["token"]);

		$this->assertTrue("T_WORD" == $lexerResult[2]["token"]);
		$this->assertTrue("patientMobile" == $lexerResult[2]["match"]);

		$this->assertTrue("T_COMMA" == $lexerResult[3]["token"]);

		$this->assertTrue("T_WORD" == $lexerResult[4]["token"]);
		$this->assertTrue("customers" == $lexerResult[4]["match"]);
	}

	public function testResourceWithFilters()
	{
		$string = "patient(@filters=filterOne eq 'filter term ')";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("patient" == $lexerResult[0]["match"]);

		$this->assertTrue("T_FILTERS" == $lexerResult[1]["token"]);
		$this->assertTrue("filterOne eq 'filter term '" == $lexerResult[1]["match"]);
	}

	/**
	 * @expectedException Exception
	 *
	 */
	public function testResourceWithIncorrectFilterKey()
	{
		//filter insstead of filters
		$string = "patient(@filter=filterOne eq 'filter term ')";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("patient" == $lexerResult[0]["match"]);

		$this->assertTrue("T_FILTERS" == $lexerResult[1]["token"]);
		$this->assertTrue("filterOne eq 'filter term '" == $lexerResult[1]["match"]);
	}

	/**
	 * @expectedException Exception
	 *
	 */
	public function testResourceWithIncorrectFormat()
	{
		//no ( b4 @filters
		$string = "patient@filters=filterOne eq 'filter term ')";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);
	}

	public function testResourceWithFields()
	{
		$string = "patient(@fields=fieldOne,fieldtow,fieldthre)";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("patient" == $lexerResult[0]["match"]);

		$this->assertTrue("T_FIELDS" == $lexerResult[1]["token"]);
		$this->assertTrue("fieldOne,fieldtow,fieldthre" == $lexerResult[1]["match"]);
	}

	public function testResourceWithSort()
	{
		$string = "patient(@orderby=fieldOne,-fieldtow)";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("patient" == $lexerResult[0]["match"]);

		$this->assertTrue("T_ORDERBY" == $lexerResult[1]["token"]);
		$this->assertTrue("fieldOne,-fieldtow" == $lexerResult[1]["match"]);
	}

	public function testResourceWithFiltersAndFieldsAndSort()
	{
		$string = "patient(@orderby=fieldOne,-fieldtow)(@fields=fieldOne,fieldtow,fieldthre)(@filters=filterOne eq 'filter term ')";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("patient" == $lexerResult[0]["match"]);

		$this->assertTrue("T_ORDERBY" == $lexerResult[1]["token"]);
		$this->assertTrue("fieldOne,-fieldtow" == $lexerResult[1]["match"]);

		$this->assertTrue("T_FIELDS" == $lexerResult[2]["token"]);
		$this->assertTrue("fieldOne,fieldtow,fieldthre" == $lexerResult[2]["match"]);

		$this->assertTrue("T_FILTERS" == $lexerResult[3]["token"]);
		$this->assertTrue("filterOne eq 'filter term '" == $lexerResult[3]["match"]);
	}

	public function testResourceWithFiltersAndFieldsAndSortForSecondResource()
	{
		$string = "customer,patient(@orderby=fieldOne,-fieldtow)(@fields=fieldOne,fieldtow,fieldthre)(@filters=filterOne eq 'filter term ')";
		$lexerResult = yehiaHamid\easyParse\parsers\embed\Lexer::run($string);

		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("customer" == $lexerResult[0]["match"]);

		$this->assertTrue("T_COMMA" == $lexerResult[1]["token"]);

		$this->assertTrue("T_WORD" == $lexerResult[2]["token"]);
		$this->assertTrue("patient" == $lexerResult[2]["match"]);
		
		$this->assertTrue("T_ORDERBY" == $lexerResult[3]["token"]);
		$this->assertTrue("fieldOne,-fieldtow" == $lexerResult[3]["match"]);

		$this->assertTrue("T_FIELDS" == $lexerResult[4]["token"]);
		$this->assertTrue("fieldOne,fieldtow,fieldthre" == $lexerResult[4]["match"]);

		$this->assertTrue("T_FILTERS" == $lexerResult[5]["token"]);
		$this->assertTrue("filterOne eq 'filter term '" == $lexerResult[5]["match"]);
	}
}
