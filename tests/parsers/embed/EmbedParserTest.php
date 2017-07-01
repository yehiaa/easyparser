<?php 

class EmbedParserTest extends \PHPUnit_Framework_TestCase
{
    public function getLexerResult($string)
    {
        return yehiaHamid\easyParse\parsers\embed\Lexer::run($string);
    }

    public function testGetResources()
    {
        $lexerResult = $this->getLexerResult("resourceOne,resource2");
        $parserResult = yehiaHamid\easyParse\parsers\embed\Parser::run($lexerResult);
        //var_dump($parserResult);
        $this->assertEquals("resourceOne", $parserResult[0]["resource"]);
        $this->assertEquals("resource2", $parserResult[1]["resource"]);
    }

    public function testFilter()
    {
        $string = "resourceOne,resource2(@filters=fieldOne eq 'filter term here')";
        $lexerResult = $this->getLexerResult($string);
        $parserResult = yehiaHamid\easyParse\parsers\embed\Parser::run($lexerResult);

        $this->assertEquals("resourceOne", $parserResult[0]["resource"]);
        $this->assertEquals("resource2", $parserResult[1]["resource"]);
        $this->assertEquals("fieldOne eq 'filter term here'", $parserResult[1]["filters"]);
    }

    public function testFieldsAndSort()
    {
        $string = "resourceOne(@orderby=fieldx,-fieldy),resource2(@fields=fieldOne,fieldTwo,Field3)(@orderby=fieldx,-fieldone)";
        $lexerResult = $this->getLexerResult($string);
        $parserResult = yehiaHamid\easyParse\parsers\embed\Parser::run($lexerResult);
        
        $this->assertEquals("resourceOne", $parserResult[0]["resource"]);
        $this->assertEquals("fieldx,-fieldy", $parserResult[0]["orderby"]);
        $this->assertEquals("resource2", $parserResult[1]["resource"]);
        $this->assertEquals("fieldOne,fieldTwo,Field3", $parserResult[1]["fields"]);
        $this->assertEquals("fieldx,-fieldone", $parserResult[1]["orderby"]);
    }

}

