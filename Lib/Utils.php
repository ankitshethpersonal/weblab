<?php
/* @File :  to set the db configuration */
namespace Weblab\Lib;

class Utils
{
    static function dateInfo() {
        
        $date = \Date("Y-m-d");
        $time = \Date("H:i");
        
        $dateTime = \Date("Y-m-d H:i:s");
        
        return $returnValues = array("date" => $date,
                                     "time" => $time,
                                     "dateTime" => $dateTime,
                                    );
    }

    static function checkDefaultTime($loginTime) {
        $diff = strtotime($loginTime) - strtotime(DEFAULT_LOGIN_TIME);
        try {          
            if ($diff > 0) {    
                $diff = \Date("H:i", $diff);
                return $diff;
            }
        
        } catch (Exception $ex) {
            //@ToDo: log error
            return true;
        }
        
        return false;
    }
    
    static function getHeadersInfo() {
        if (!function_exists('getallheaders')) 
        { 
            $headers = array (); 
            foreach ($_SERVER as $name => $value) 
            { 
                if (substr($name, 0, 5) == 'HTTP_') 
                { 
                    $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value; 
                } 
            } 
            return $headers; 
            
        } else {
            return getallheaders();
        }
    }
   
}


?>
