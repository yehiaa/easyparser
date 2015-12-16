<?php namespace yehiaHamid\easyParse\parsers\filter;

class Lexer extends \yehiaHamid\easyParse\parsers\Lexer {
    // "field1 eq 'what ever', field2 nq 'term two'"

    protected static $_terminals = array(
        "/^(eq|ne|lt|gt|like)/" => "T_OPERATOR",
        "/^(\w+)/" => "T_WORD",
        "/^(\s+)/" => "T_WHITESPACE",
        "/^(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/" => "T_QUOTED", 
        "/^(,)/" => "T_COMMA"
    );

}
