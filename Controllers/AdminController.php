<?php
/* @File : custom file for team data request */
namespace Weblab\Controllers;

use Weblab\Lib;
use Weblab\Lib\Utils as Utils;
use Weblab\Services;

class AdminController
{
	public $_dbObject;
	private $_dbTable;

	public function __construct() 
	{		
		//$this->_dbObject = $db;		
		$this->_dbTable  = 'system_users';
                $this->_dbChildTable  = 'system_user_timings';
	}

	/* @Function : to get the data from db and return as per request
	*  @params   : username : null or integer
	   @returns  : return array of data or false
	*/
	public function users(array $requestData)
	{             
            try {
                // call model object
                $objUserService =  \Weblab\Lib\UserContainer::getInstance();
                
                $type = "all";
                if (!empty($requestData['userName'])) {
                    $type = 'user';
                }
                
                $avgTimeData = $objUserService->getAverageTime($requestData, $type);
                
                $page = 0;
                if (isset($requestData['page'])) {
                    $page = intval($requestData['page']);
                }
                
                $totalRecords = $objUserService->listAll($requestData, true);
                $data = $objUserService->listAll($requestData, false, $page);                
                 
                if ($data && count($data)) {
                    $returnData = array();
                                      
                    $returnData = array();
                    $returnData['perPage']      = USERS_PER_PAGE;
                    $returnData['totalRecords'] = $totalRecords;
                    $returnData['currentPage']  = $page;
                    
                    if (!empty($avgTimeData)) {
                        $returnData['averageLoginTime'] = \Date("H:i", strtotime($avgTimeData['avgLoginTime']));
                    }
                    
                    
                    $total = count($data);

                    $filterObj = array();
                    $obj = array();
                    
                    for ($t = 0; $t < $total; $t++) {
                        
                        $filterObj['id'] = $data[$t]['id'];
                        $filterObj['userName'] = $data[$t]['username'];
                        $filterObj['loginDate'] = $data[$t]['login_date'];
                        $filterObj['loginTime'] = $data[$t]['login_time'];
                        $filterObj['isLate']    = $data[$t]['is_late'];
                        $filterObj['lateByTime'] = $data[$t]['late_by_time'];
                        
                        array_push($obj, $filterObj);
                    }
                    
                    $returnData['userData'] = $obj;
                    
                    return $returnData;
                    
                } else {
                    return array("status"=> "200" ,"message" => "No user found");
                }
            } catch (Exception $ex) {
                /* @ToDo : log error to monolog - to cloudwatch */
                return array("status"=> "503" ,"message" => "Server Error");
            }            
        }
        
        public function avgtime($requestData = array()) {
            
            $type = "all";
            if (!empty($requestData['type'])) {
                $type = $requestData['type'];
            }
            
            try {
                // call model object
                $objUserService =  \Weblab\Lib\UserContainer::getInstance();
                
                $data = $objUserService->getAverageTime($requestData, $type);
                
                if ($data) {
                    $total = count($data);

                    $filterObj = array();
                    $obj = array();
                    for ($t = 0; $t < $total; $t++) {
                        
                        if ($type == "user") {
                            $filterObj['userName'] = $data[$t]['username'];
                            $filterObj['averageLoginTime'] = \Date("H:i", strtotime($data[$t]['avgLoginTime']));
                        } else {
                            $filterObj['teamAverageLoginTime'] = \Date("H:i", strtotime($data[$t]['avgLoginTime']));
                        }                        
                        
                        array_push($obj, $filterObj);
                    }
                    
                    $returnData['timeData'] = $obj;
                    
                    return $returnData;
                } else {
                    return array("status"=> "200" ,"message" => "No records found !!");
                }
                
            } catch (Exception $ex) {
                /* @ToDo : log error to monolog - to cloudwatch */
                return array("status"=> "503" ,"message" => "Server Error");
            }
        }
        
        
        public function analytics($requestData = array()) {
            
            try {
                
                $type = "";
                if (!empty($requestData['userName'])) {
                    $type = "user";
                }
                // call model object
                $objUserService =  \Weblab\Lib\UserContainer::getInstance();
                
                $data = $objUserService->getAverageTime($requestData, $type);
                
                if ($data) {
                    $total = count($data);

                    $filterObj = array();
                    $obj = array();
                    for ($t = 0; $t < $total; $t++) {
                        
                        if ($type == "user") {
                           
                            $filterObj['userName'] = $data[$t]['username'];
                            $filterObj['averageLoginTime'] = \Date("H:i", strtotime($data[$t]['avgLoginTime']));
                            
                            // get total user days count and isLate count
                            $loginData = $objUserService->getLoginCount($requestData, $type);
                           
                            $returnLoginData = array();
                            $returnLoginData['totalLogins'] = 0;
                            $returnLoginData['lateLogins']  = 0;
                            if ($loginData && count($loginData)) {
                                for ($t = 0; $t < count($loginData); $t++) {
                                    $returnLoginData['totalLogins'] += $loginData[$t]['total'];
                                    if ($loginData[$t]['is_late'] == 1) {
                                        $returnLoginData['lateLogins']  += $loginData[$t]['total'];
                                    }
                                }
                            }
                            $filterObj['userLoginData'] = $returnLoginData;
                        } else {
                            $filterObj['teamAverageLoginTime'] = \Date("H:i", strtotime($data[$t]['avgLoginTime']));
                        }                        
                        
                        array_push($obj, $filterObj);
                    }
                    
                    $returnData['timeData'] = $obj;
                    
                    return $returnData;
                } else {
                    return array("status"=> "200" ,"message" => "No records found !!");
                }
                
            } catch (Exception $ex) {
                /* @ToDo : log error to monolog - to cloudwatch */
                return array("status"=> "503" ,"message" => "Server Error");
            }
        }
        
}

?>