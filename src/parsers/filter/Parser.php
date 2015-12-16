<?php namespace yehiaHamid\easyParse\parsers\filter; 

/**
 * Filter Parser
 */
class Parser 
{
    private static $_lexerResult; 
    private static $filters;
    
    public static function run($lexerResult)
    {
        self::$_lexerResult = $lexerResult;
		self::$filters = null ;
        $count = count($lexerResult);
		if($count < 3 ) //exception invalid argument some arguments are missing
		{
            throw new \Exception("invalid argument some arguments are missing", 1);
		}elseif(($count > 3) && ($count % 3 != 1) ){
            throw new \Exception("invalid argument some arguments are missing", 1);
		}
        return self::excute($lexerResult);
    }
    
    private static function validate(array $valuesToParse)
    {
        if (!isset($valuesToParse["field"])) {
            throw new \Exception("field not found ", 1);
        }
        
        if (!isset($valuesToParse["operator"] )) {
            throw new \Exception("operator not found ", 1);            
        }
        
        if (!isset($valuesToParse["value"]) ) {
            throw new \Exception("value not found ", 1);            
        }
        
        return $valuesToParse ;
    }
    
    private static function excute()
    {
        // this is a complete query 
        // word > operator > Quoted 
        // word > operator > word || quoted
        $count = count(self::$_lexerResult);
        $filter = [] ;
        foreach (self::$_lexerResult as $token) {
            
            if ($token["token"] == "T_WORD") {
                $filter["field"] = $token["match"];
                continue;
            }
            
            if ($token["token"] == "T_OPERATOR") {
                $filter["operator"] = $token["match"];
                continue;
            }
            
            if ($token["token"] == "T_QUOTED") {
                
                $filter["value"] = trim($token["match"], "'\"");
                self::$filters [] = self::validate($filter);
                $filter = [] ;
                continue;
            }
            
            if ($token["token"] == "T_COMMA") {
                $filter = [] ;
            }
        }
        
        return self::$filters;
    }
    
}
