<?php

class FilterLexerTest extends PHPUnit_Framework_TestCase
{
	public function testWorkingFilterSingleQuote()
	{
		$data = "fieldone ne 'this is filter term '";
		$lexerResult = yehiaHamid\easyParse\parsers\filter\Lexer::run($data);			
		
		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("T_OPERATOR" == $lexerResult[1]["token"]);
		$this->assertTrue("T_QUOTED" == $lexerResult[2]["token"]);
	}


	public function testWorkingFilterSingleQuoteContainsDoubleQuote()
	{
		$data = "fieldone ne 'this is filter\"s term '";
		$lexerResult = yehiaHamid\easyParse\parsers\filter\Lexer::run($data);			
		
		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("T_OPERATOR" == $lexerResult[1]["token"]);
		$this->assertTrue("T_QUOTED" == $lexerResult[2]["token"]);
	}

	public function testWorkingFilterDoubleQuote()
	{
		$data = "fieldone ne \"this is filter term \"";
		$lexerResult = yehiaHamid\easyParse\parsers\filter\Lexer::run($data);			
		
		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("T_OPERATOR" == $lexerResult[1]["token"]);
		$this->assertTrue("T_QUOTED" == $lexerResult[2]["token"]);
	}


	public function testWorkingFilterDoubleQuoteContainsSingleQuote()
	{
		$data = "fieldone ne \"this is filter's term \"";
		$lexerResult = yehiaHamid\easyParse\parsers\filter\Lexer::run($data);			
		
		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("T_OPERATOR" == $lexerResult[1]["token"]);
		$this->assertTrue("T_QUOTED" == $lexerResult[2]["token"]);
	}

	public function testInvalidFieldWithSpace()
	{
		$data = "field one ne 'this is filter term '";
		$lexerResult = yehiaHamid\easyParse\parsers\filter\Lexer::run($data);			
	}
}
