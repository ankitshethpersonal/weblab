<?php
/* @File : custom file for team data request */
namespace Weblab\Controllers;

use Weblab\Lib;
use Weblab\Lib\Utils as Utils;
use Weblab\Services;

class UserController
{

	public function __construct() 
	{					
		
	}

	/* @Function : to get the data from db and return as per request
	*  @params   : username : null or string
	   @returns  : return array of data or false
	*/
	public function post(array $requestData)
	{
            if (empty($requestData) || empty($requestData['userName'])) {
                return array("status"=> "400" ,"message" => "Please enter username"); 
            }
            

            try {
                // call model object
                //$objUserService = new \Weblab\Services\Custom_User($this->_dbObject);
                $objUserService =  \Weblab\Lib\UserContainer::getInstance();
                
                $data = $objUserService->get($requestData);
                         
                if ($data && count($data)) {
                    if (!empty($data['id'])) {
                        $objUserTimingsService = \Weblab\Lib\UserTimingsContainer::getInstance();
            
                        $timeData = $objUserTimingsService->get($data);
                                              
                        if (!empty($timeData)) {
                            return array("status"=> "200" ,"message" => "Welcome back, your have already logged in today at @".$timeData['login_time']);
                        } else {
                            $dateInfo = \Weblab\Lib\Utils::dateInfo();
                            $currentDate = $dateInfo['date'];
                            $currentTime = $dateInfo['time'];
                                                       
                            $message = "";
                            $isLate = false;
                            $lateBy = \Weblab\Lib\Utils::checkDefaultTime($currentTime);
                            if ($lateBy) {
                                $message = " ,but you are late for today, please make sure to come before @".DEFAULT_LOGIN_TIME;                                                                     
                                $isLate = true;                   
                                $data['isLate'] = true;
                                $data['lateBy'] = $lateBy;
                            } else {
                                $data['isLate'] = false;
                                $data['lateBy'] = $lateBy;
                            }
                            
                            // enter data to db
                            $newData = $objUserTimingsService->saveUserTimings($data, $dateInfo);
                            
                            if (!empty($newData) && !empty($newData['loginTime'])) {                               
                                return array("status"=> "200" ,"message" => "Welcome, have a produtive day ahead !!" .$message, "data" => array("isLate" => $isLate, "timeDiff" => $lateBy));
                            } else {
                                return array("status"=> "500" ,"message" => "Server Error - Data insertion...");
                            }
                            
                        }
                    }
                    
                } else {
                    return array("status"=> "400" ,"message" => "User Not found");
                }
            } catch (Exception $ex) {
                /* @ToDo : log error to monolog - to cloudwatch */
                return array("status"=> "503" ,"message" => "Server Error");
            }            
        }

}

?>