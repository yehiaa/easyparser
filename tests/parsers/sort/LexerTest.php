<?php

/**
 * test filter parser and lexer
 */
class SortTest extends PHPUnit_Framework_TestCase
{
	public function testWorkingLexer()
	{
		$string = "fieldx, fieldy,-fieldz";
		$lexerResult = yehiaHamid\easyParse\parsers\sort\Lexer::run($string);
		$this->assertTrue("T_WORD" == $lexerResult[0]["token"]);
		$this->assertTrue("T_COMMA" == $lexerResult[1]["token"]);
		$this->assertTrue("T_WORD" == $lexerResult[2]["token"]);
		$this->assertTrue("T_COMMA" == $lexerResult[3]["token"]);
		$this->assertTrue("T_DESCORDER" == $lexerResult[4]["token"]);
		$this->assertTrue("T_WORD" == $lexerResult[5]["token"]);
	}
	/**
	 * @expectedException Exception
	 *
	 */
	public function testInvalidCharException()
	{
		$string = "fieldx, fieldy,=fieldz";
		$lexerResult = yehiaHamid\easyParse\parsers\sort\Lexer::run($string);
	}

}
