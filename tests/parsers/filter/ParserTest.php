<?php


class ParserTest extends PHPUnit_Framework_TestCase
{
	public function getLexerData($string)
	{
		return yehiaHamid\easyParse\parsers\filter\Lexer::run($string);	
	}
	
	public function testWorkingParserWithNoComma()
	{
		$string = "fieldOne ne 'this is filter term '";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
		$this->assertArrayHasKey("field", $parserData[0]);
		$this->assertArrayHasKey("operator", $parserData[0]);
		$this->assertArrayHasKey("value", $parserData[0]);
	}

	public function testWorkingParserWithComma()
	{
		$string = "fieldOne ne 'this is filter term ', fieldtwo lt 'filter term '";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
		$this->assertArrayHasKey("field", $parserData[0]);
		$this->assertArrayHasKey("operator", $parserData[0]);
		$this->assertArrayHasKey("value", $parserData[0]);
		
		$this->assertArrayHasKey("field", $parserData[1]);
		$this->assertArrayHasKey("operator", $parserData[1]);
		$this->assertArrayHasKey("value", $parserData[1]);
	}

	/**
	 * @expectedException Exception
	 *
	 */
	public function testExceptionNoField()
	{
		$string = "ne 'this is filter term '";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
	}

	
	/**
	 * @expectedException Exception
	 *
	 */
	public function testExceptionNoOperator()
	{
		$string = "fieldOne 'this is filter term '";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
	}

	/**
	 * @expectedException Exception
	 *
	 */
	public function testExceptionInvalidOperator()
	{
		$string = "fieldOne ll 'this is filter term '";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
	}

	/**
	 * @expectedException Exception
	 *
	 */
	public function testExceptionNoValue()
	{
		$string = "fieldOne ne";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
	}


	/**
	 * @expectedException Exception
	 *
	 */
	public function testExceptionNoValueWithComma()
	{
		$string = "fieldOne ne 'filter term here ', fieldtwo eq ";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
	}


	/**
	 * @expectedException Exception
	 *
	 */
	public function testExceptionNoOperatorAfterComma()
	{
		$string = "fieldOne ne 'filter term here ', fieldtwo 'this is term '";
		$parserData = yehiaHamid\easyParse\parsers\filter\Parser::run($this->getLexerData($string));
	}

}

