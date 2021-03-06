<?php
/* @File : custom file for team data request */
namespace Weblab\Services;

use Weblab\Lib;
use Weblab\Lib\Utils as Utils;
use Weblab\Lib\DB;

class Custom_User implements ResourceInterface
{
    public $_dbObject;
    private $_dbTable;
    private $_dbChildTable;

    public function __construct() 
    {		
            //$this->_dbObject = $db;		
            $this->_dbTable  = 'system_users';
            $this->_dbChildTable  = 'system_user_timings';
    }
    
    public function setDB($db) {
        $this->_dbObject = $db;
    }

    /* @Function : to get the data from db and return as per request
    *  @params   : id : null or integer
       @returns  : return array of data or false
    */
    public function get(array $requestData)
    {        
        $userName = $requestData['userName'];

        $selectQuery = "SELECT * FROM ".$this->_dbTable."";

        $dateInfo = Utils::dateInfo();

        $date = $dateInfo['date'];
        $time = $dateInfo['time'];

        $selectQuery .= " WHERE 1 = 1 ";

        if ($userName != "") {
                $selectQuery .=" AND username = :userName ";
        }

        try {		    

            $stmt = $this->_dbObject->dbh->prepare($selectQuery);

            if ($userName != "") {		    	
                $stmt->bindParam(':userName', $userName, \PDO::PARAM_STR); // bind to string param only (as id is integer)
            }  

            if ($stmt->execute()) {		    	
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);	
                if ($data && count($data)) {

                    return $data[0];
                } else {
                    return false;
                }
            } else {
                //return array("status"=> "500" ,"message" => "Server Error");
                return false;
            }   
        } catch (Exception $e) {
                // can log here the error in log file with $e->getCode and $e->getMessage
                return false;
        }
    }
    
    /* @Function : to get the data from db and return as per request
    *  @params   : id : null or integer
       @returns  : return array of data or false
    */
    public function listAll(array $filterData, $isTotal = false, $currentPage = 0)
    {
        if ($isTotal) {
            $selectQuery = "SELECT count(su.id) as total FROM ".$this->_dbTable." as su ";
        } else {
            $selectQuery = "SELECT * FROM ".$this->_dbTable." as su ";
        }
        
        

        $dateInfo = Utils::dateInfo();

        $date = $dateInfo['date'];
        $time = $dateInfo['time'];
        
        if (empty($filterData['loginDate'])) {
            $filterData['loginDate'] = $date;
        }
        
        $selectQuery .= " INNER JOIN  $this->_dbChildTable stu "
                . " ON su.id = stu.user_id ";
        
        $selectQuery .= " WHERE 1 = 1 ";
                
        try {		    
            
            $keys = array_keys($filterData);
           
            if (!empty($filterData['loginDate'])) {
                $selectQuery .=" AND login_date = :loginDate";               
            }
            
            if (!empty($filterData['userName'])) {
                $selectQuery .=" AND username LIKE :userName";               
            }

            if (in_array('isLate',$keys)) {
                $selectQuery .=" AND is_late = :isLate ";                    
            }

            
            if (!$isTotal) {  
                $selectQuery .=" ORDER BY username asc";

                $selectQuery .= " LIMIT :skip, :offset";
                
            }
            
            $stmt = $this->_dbObject->dbh->prepare($selectQuery);
                        
            if (!empty($filterData['userName'])) {
                $val = $filterData['userName']."%";
                $stmt->bindParam(':userName', $val, \PDO::PARAM_STR); 
            }

            if (in_array('isLate',$keys)) {                    
                $stmt->bindParam(':isLate', $filterData['isLate'], \PDO::PARAM_BOOL); 
            }

            if (!empty($filterData['loginDate'])) {
                $stmt->bindParam(':loginDate', $filterData['loginDate'], \PDO::PARAM_STR); 
            }
            
            if (!$isTotal) {
                $offSet = USERS_PER_PAGE;
                $limit = $currentPage;
                
                if ($currentPage > 0) {
                    $limit = ($currentPage * USERS_PER_PAGE);
                }
                
                $stmt->bindParam(':skip', $limit, \PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offSet, \PDO::PARAM_INT);
                
                $orderBy = "username";
                //$stmt->bindParam(":orderBy", $orderBy, \PDO::PARAM_STR);
                
                $direction = "asc";
               // $stmt->bindParam(":direction", $direction, \PDO::PARAM_STR);
            }
    
            if ($stmt->execute()) {		    	
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);	
                if ($data && count($data)) {
                    if ($isTotal) {
                        return $data[0]['total'];
                    }
                    return $data;
                } else {
                    return false;
                }
            } else {
                //return array("status"=> "500" ,"message" => "Server Error");
                return false;
            }   
        } catch (Exception $e) {
                // can log here the error in log file with $e->getCode and $e->getMessage
                return false;
        }
    }
    
    function getAverageTime(array $filterData, $flag = "all") {
        
        //SELECT from_unixtime( avg( unix_timestamp(login_time) ) ) FROM system_user_timings 
        if ($flag == 'user') {
            $selectQuery = "SELECT username, from_unixtime( avg( unix_timestamp(login_time) ) ) as avgLoginTime "
                . " FROM $this->_dbTable as su " ;
        } else {
            $selectQuery = "SELECT from_unixtime( avg( unix_timestamp(login_time) ) ) as avgLoginTime "
                . " FROM $this->_dbTable as su " ;
        }

        
        $dateInfo = Utils::dateInfo();

        $date = $dateInfo['date'];
        $time = $dateInfo['time'];
        
        $selectQuery .= " INNER JOIN  $this->_dbChildTable stu "
                . " ON su.id = stu.user_id ";
        
        $selectQuery .= " WHERE 1 = 1 ";
                
        try {		    
            
            $keys = array_keys($filterData);
           
            if (!empty($filterData['loginDate'])) {
                $selectQuery .=" AND login_date = :loginDate";               
            }
            
            if (!empty($filterData['userName'])) {
                $selectQuery .=" AND username LIKE :userName";               
            }
            
            if ($flag == "user") {
                $selectQuery .= " GROUP BY stu.user_id";
            }     
            
            //echo $selectQuery; exit;
           
            $stmt = $this->_dbObject->dbh->prepare($selectQuery);
                        
            if (!empty($filterData['userName'])) {
                $val = $filterData['userName']."%";
                $stmt->bindParam(':userName', $val, \PDO::PARAM_STR); 
            }

            if (!empty($filterData['loginDate'])) {
                $stmt->bindParam(':loginDate', $filterData['loginDate'], \PDO::PARAM_STR); 
            }
            

            if ($stmt->execute()) {		    	
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);	
                if ($data && count($data)) {

                    return $data;
                } else {
                    return false;
                }
            } else {
                //return array("status"=> "500" ,"message" => "Server Error");
                return false;
            }   
        } catch (Exception $e) {
                // can log here the error in log file with $e->getCode and $e->getMessage
                return false;
        }
        
    }
    
    public function getLoginCount(array $filterData, $flag = "all") {
        $userName = $filterData['userName'];
        
        $selectQuery = "SELECT user_id, is_late, count(stu.user_id) as total "
                . " FROM $this->_dbTable as su " ;
        
        $dateInfo = Utils::dateInfo();

        $date = $dateInfo['date'];
        $time = $dateInfo['time'];
        
        $selectQuery .= " INNER JOIN  $this->_dbChildTable stu "
                . " ON su.id = stu.user_id ";
        
        $selectQuery .= " WHERE 1 = 1 ";
                
        try {		    
            
            $keys = array_keys($filterData);
           
            if (!empty($filterData['loginDate'])) {
                $selectQuery .=" AND login_date = :loginDate";               
            }
            
            if (!empty($filterData['userName'])) {
                $selectQuery .=" AND username LIKE :userName";               
            }
            
            if ($flag == "user") {
                $selectQuery .= " GROUP BY stu.user_id, stu.is_late";
            }     
            
            //echo $selectQuery; exit;
           
            $stmt = $this->_dbObject->dbh->prepare($selectQuery);
                        
            if (!empty($filterData['userName'])) {
                $val = $filterData['userName']."%";
                $stmt->bindParam(':userName', $val, \PDO::PARAM_STR); 
            }
            
            if (!empty($filterData['loginDate'])) {
                $stmt->bindParam(':loginDate', $filterData['loginDate'], \PDO::PARAM_STR); 
            }
            

            if ($stmt->execute()) {		    	
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);	
                if ($data && count($data)) {
                    return $data;
                } else {
                    return false;
                }
            } else {
                //return array("status"=> "500" ,"message" => "Server Error");
                return false;
            }   
        } catch (Exception $e) {
                // can log here the error in log file with $e->getCode and $e->getMessage
                return false;
        }
        
    }

}
?>
