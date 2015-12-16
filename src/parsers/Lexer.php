<?php namespace yehiaHamid\easyParse\parsers;

class Lexer {

    protected static $_terminals = array();

	public static function run($source) {
		$tokens = array();

		$offset = 0;
		while($offset < strlen($source)) {
			$result = static::_match($source, $offset);
			if($result === false) {
				throw new \Exception("Unable to parse at offset " . ($offset). " string : " . $source );
			}

			// ignore whitespace
			if($result["token"] != "T_WHITESPACE") {
				$tokens[] = $result;    
			}

			$offset += strlen($result['match0']);
		}

		if (empty($tokens)) {
			throw new \Exception("nothing to parse ");	
		}

		return $tokens;
	}


    protected static function _match($source, $offset) {
        $string = substr($source, $offset);

        foreach(static::$_terminals as $pattern => $name) {
            if(preg_match($pattern, $string, $matches)) {
                return array(
                    'match' => ( !isset($matches[1]) ? $matches[0] : $matches[1] ),
                    'match0' => $matches[0],
                    'token' => $name,
                );
            }
        }

        return false;
    }
}
