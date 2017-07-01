<?php namespace yehiaHamid\easyParse\parsers\embed;

/**
 * embed Parser
 */
class Parser 
{
    private static $lexerResult; 
    private static $embeds;

    public static function run($lexerResult)
    {
        self::$embeds = null ;
        self::$lexerResult = $lexerResult;
        return self::excute($lexerResult);
    }
    
    private static function validate(array $arrayToValidate)
    {
        if (!isset($arrayToValidate["resource"])) {
            throw new \Exception("resource not found ", 1);            
        }
        
        return $arrayToValidate ;
    }
    
    private static function excute()
    {
        // this is a complete query 
        // word > operator > Quoted 
        // word > operator > word || quoted
        $embed = array() ;
        
        foreach (self::$lexerResult as $token) {
            
            switch ($token["token"]) {
                case 'T_WORD':
                    $embed["resource"] = $token["match"];
                    break;
                    
                case 'T_FILTERS':
                    $embed["filters"] = $token["match"];
                    break;
                    
                case 'T_FIELDS':
                    $embed["fields"] = $token["match"];
                    break;
                    
                case 'T_ORDERBY':
                    $embed["orderby"] = $token["match"];
                    break;
                    
                case 'T_COMMA':
                    self::$embeds [] = self::validate($embed);
                    $embed = array() ;
                    break;
                    
                default:
                    self::$embeds [] = self::validate($embed);
                    break;
            }
        }
        
        // this check if no comma in tokens , i'll assign embed to embeds array 
        if (isset($embed["resource"])) {
            self::$embeds [] = self::validate($embed);
        }

        return self::$embeds;
    }
    
}
