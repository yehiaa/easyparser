<?php namespace yehiaHamid\easyParse\parsers\sort;
/**
 * sort Lexer
 */
class Lexer extends \yehiaHamid\easyParse\parsers\Lexer {
    // "field1 eq 'what ever', field2 nq 'term two'"

    protected static $_terminals = array(
        "/^(-)/" => "T_DESCORDER",
        "/^(\w+)/" => "T_WORD",
        "/^(\s+)/" => "T_WHITESPACE",
        "/^(,)/" => "T_COMMA",
    );
}
