<?php namespace yehiaHamid\easyParse\parsers\filter;

class Lexer extends \yehiaHamid\easyParse\parsers\Lexer {
    // "field1 eq 'what ever', field2 nq 'term two'"

    protected static $terminals = array(
        "/^( eq | ne | lt | gt | like | le | ge | ilike )/" => "T_OPERATOR",
        "/^(\w+)/" => "T_WORD",
        "/^(\s+)/" => "T_WHITESPACE",
        "/^(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/" => "T_QUOTED", 
        "/^(,)/" => "T_COMMA"
    );

}
