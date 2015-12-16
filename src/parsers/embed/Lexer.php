<?php namespace yehiaHamid\easyParse\parsers\embed ;

class Lexer extends \yehiaHamid\easyParse\parsers\Lexer {
    // "field1 eq 'what ever', field2 nq 'term two'"

    protected static $_terminals = array(
        "/^(\w+)/" => "T_WORD",
        "/^(\s+)/" => "T_WHITESPACE",
        "/^\(@filters=(.*?)\)/" => "T_FILTERS",
        "/^\(@fields=(.*?)\)/" => "T_FIELDS",
        "/^\(@orderby=(.*?)\)/" => "T_ORDERBY",
        "/^(,)/" => "T_COMMA",
    );
}
