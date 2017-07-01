<?php namespace yehiaHamid\easyParse\parsers\filter; 

/**
 * Filter Parser
 */
class Parser 
{
    private static $lexerResult; 
    private static $filters;

    public static function run($lexerResult)
    {
        self::$lexerResult = $lexerResult;
        self::$filters = null ;
        $count = count($lexerResult);

        // var_dump($lexerResult);die;
        if($count < 3 ) //exception invalid argument some arguments are missing
            throw new \Exception("invalid argument some arguments are missing", 1);
        elseif(($count > 3) && (($count - 3) % 4 != 0) )
            throw new \Exception("invalid argument some arguments are missing", 1);

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
        $filter = array() ;
        foreach (self::$lexerResult as $token) {
            
            if ($token["token"] == "T_WORD") {
                if(! isset($filter["field"]))
                {
                    $filter["field"] = $token["match"];
                    continue;
                }

            }
            
            if ($token["token"] == "T_OPERATOR") {
                $filter["operator"] = trim($token["match"]);
                continue;
            }
            
            if ($token["token"] == "T_QUOTED") {
                
                $filter["value"] = trim($token["match"], "'\"");
                self::$filters [] = self::validate($filter);
                $filter = array() ;
                continue;
            }

            if ($token["token"] == "T_WORD") {
                if(isset($filter["field"]))
                {
                    $filter["value"] = trim($token["match"]);
                    self::$filters [] = self::validate($filter);
                    $filter = array() ;
                    continue;
                }

            }

            if ($token["token"] == "T_COMMA") {
                $filter = array() ;
            }
        }

        return self::$filters;
    }
    
}
