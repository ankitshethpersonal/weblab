<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';

use Weblab\Lib;

// create request consuming object - 
$requestObj = new Weblab\Lib\RequestController();

// retrieve the request object
$request = $requestObj->getRequest();

$allowed = $requestObj->checkApiKey($request);

if ($allowed) {
    
    // retrieve the data - required for post method
    $requestData = $requestObj->getData();

    $allowedClasses = array('admin', 'user', 'login', 'usertimings');
    $allwedMethods  = array('get', 'post', 'put', 'delete', 'patch', 'users', 'avgtime', 'analytics');

    // get the request data
    $className   = !empty($requestData['controller']) ? $requestData['controller'] : "";
    $method      = !empty($requestData['method']) ? $requestData['method'] : "";
    $requestData = !empty($requestData) ? $requestData : array();


    $responseData = array();

    if ($className == "" 
            || !in_array($className, $allowedClasses)
            || !in_array($method, $allwedMethods)) {

        $response = Weblab\Lib\RequestController::directRespond(503);
        echo $response;
        exit;
    } else {

        try {
            $dbObj = Weblab\Lib\DB::getInstance();    
        } catch (PDOException $e) {
            // can log here the error in log file with $e->getCode and $e->getMessage
            $response = Weblab\Lib\RequestController::directRespond(500);

            echo $response;
            exit;
        }

        try {
            $classObj = Weblab\Lib\CreateResource::getInstance($dbObj, $className);    
        } catch (Exception $e) {
            // can log here the error in log file with $e->getCode and $e->getMessage
            $response = Weblab\Lib\RequestController::directRespond(503);

            echo $response;
            exit;
        }    

        $passRequestData = $requestData;
        unset($passRequestData['controller']);
        unset($passRequestData['method']);



        $methodResponse = $classObj->$method($passRequestData);
        $response = Weblab\Lib\RequestController::respond($methodResponse); 
        
    } 
} else {
    $response = array("status" => 403, "message" => "Forbidden");
}

$jsonResponse = json_encode($response);
// or you can take the json/xml respnose back and echo here
// set the content type- actually need to send the VIEW file


header("Status:".$response['status']);
header('Content-type: ' . Weblab\Lib\RequestController::$_contentType . '; charset=utf-8');
// enable CORS (optional)
header("Access-Control-Allow-Origin: *");

echo $jsonResponse;
exit;
