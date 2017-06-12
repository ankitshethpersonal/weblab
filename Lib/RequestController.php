<?php
/* @File : to consume the request and analyze it, parse it - provide method and request params of the request*/
namespace Weblab\Lib;

require_once('config.php');

class RequestController
{
    private $data;
    private $http_accept;
    private $method;
    private $request;
    private $id;
    public static $_contentType;
     
    public function __construct()
    {
        $this->data              = array();
        self::$_contentType      = (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'json';
        $this->method            = strtolower( $_SERVER['REQUEST_METHOD'] );

        
        // do as per method - rest verbs
        $this->request = array();
        switch ( $this->method )
        {
            case 'get':                
                $this->data = $_GET;   
                $this->setData($this->data);    
            break;
        
            case 'post':
                $getData  = $_GET; // to get the url variables
                $postData = json_decode( file_get_contents( 'php://input' ), true ); // to get the data
                
                $this->data = array_merge($getData, $postData);                
                $this->setData($this->data);  
            break;
        
            case 'put':
                $this->data = json_decode( file_get_contents( 'php://input' ), true ); 
                $this->setData($this->data);       
            break;
        
            case 'delete':
                $this->data = json_decode( file_get_contents( 'php://input' ), true );
                $this->setData($this->data);  
            break;
        
            default:
                die( Controller::respond( 400 ) );
            break;
        }
         
        // here you can put your authentication code, based on your secret key (pri./pub keys or other
        // mecanism to validate the api) 
        //$returnAuthCode = $this->authenticateAPI($requestObj);
        
    }

    /* @Function : to response the rest api request */ 
    public static function respond($data = array())
    {
        // build the status header        
        // for security you can replace with command serparated specific domains
        //header("Access-Control-Allow-Origin: ".$allowed_domains); // comma separated
            if (self::$_contentType == "json") {
                if ($data && count($data)) {
                    if (!empty($data['status'])) {
                        $status = $data['status'];
                        if (!empty($data['status'])) {
                           $message = $data['message'];
                        } else {
                            $message = self::getHttpCode($status);
                        }
                        
                        $responseData['status']  = $status;
                        $responseData['message']  = $message;
                        if(!empty($data['data'])) {
                            $responseData['data']  = $data['data'];
                        }
                    } else {
                        $responseData['status']  = 200;
                        $responseData['message'] = self::getHttpCode(200);
                        $responseData['data']  = json_encode($data);
                    }
                                        
                    
                } else {
                    $status = 204;
                    $responseData['status']  = 204;
                    $responseData['message'] = self::getHttpCode(204);
                }                
            }    

           return $responseData;
     } 

     public static function directRespond($status)
    {
        // build the status header        
        // for security you can replace with command serparated specific domains
        //header("Access-Control-Allow-Origin: ".$allowed_domains); // comma separated
            $responseData = array();

            $responseData['status']  = $status;
            $responseData['message'] = self::getHttpCode($status);
            

            return json_encode($responseData);
            
     } 
             
   
     
    public static function getHttpCode($status) // this has been taken from internet :)
    {
        // these could be stored better, in an .ini file and loaded via parse_ini_file()
        $codes = Array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
     
    public function getData()
    {
        return $this->data;
    }
    
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
     
    public function getRequest()
    {
        return $this->request;
    }
     
    public function getMethod()
    {
        return $this->method;
    }
     
    public function getHttpAccept()
    {
        return $this->http_accept;
    }
    
    public function checkApiKey($request) {
       $headers = \Weblab\Lib\Utils::getHeadersInfo();
       
       if (!empty($headers['x-api-key'])) {
           if ($headers['x-api-key'] === API_KEY) {
               return true;
           }
       }
       
       return false;       
    }
     
}

?>
