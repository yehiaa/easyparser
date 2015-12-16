<?php namespace yehiaHamid\easyParse\parsers\sort;

/**
 * sort Parser
 */
class Parser 
{
    private static $_lexerResult; 
    private static $orderBys;
    
    
    public static function run($lexerResult)
    {
        self::$_lexerResult = $lexerResult;
		self::$orderBys = null ;
        return self::excute($lexerResult);
    }
    
    private static function validate(array $valuesToParse)
    {
        if (!isset($valuesToParse["field"])) {
            throw new \Exception("field not found ", 1);            
        }

        if (!isset($valuesToParse["direction"])) {
			$valuesToParse["direction"] = null ;
        }
        
        return $valuesToParse ;
    }
    
    private static function excute()
    {
        // this is a complete query 
        //field,-fieldtwo (-) means descending order 
        $cont = count(self::$_lexerResult);
        $orderBy = [] ;
        foreach (self::$_lexerResult as $token) {
            
            if ($token["token"] == "T_WORD") {
                $orderBy["field"] = $token["match"];
                continue;
            }
            
            if ($token["token"] == "T_DESCORDER") {
                $orderBy["direction"] = "desc"; 
                continue;
            }
            
            if ($token["token"] == "T_COMMA") {
                self::$orderBys [] = self::validate($orderBy);
                $orderBy = [] ;
            }
        }

        // this check if no comma in tokens , i'll assign embed to embeds array 
        if (isset($orderBy["field"])) {
            self::$orderBys [] = self::validate($orderBy);
        }

        return self::$orderBys;
    }
    
}
