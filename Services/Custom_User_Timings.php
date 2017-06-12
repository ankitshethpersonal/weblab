<?php
/* @File : custom file for team data request */
namespace Weblab\Services;

use Weblab\Lib;
use Weblab\Lib\Utils as Utils;

class Custom_User_Timings implements ResourceInterface
{
	public $_dbObject;
	private $_dbTable;
        private $_dbParentTable;

	public function __construct() 
	{		
	    $this->_dbTable  = 'system_user_timings';
            $this->_dbParentTable = 'system_users';
	}
        
        public function setDB($db) {
            $this->_dbObject = $db;
        }
    
	/* @Function : to get the data from db and return as per request
	*  @params   : id : null or integer
	   @returns  : return array of data or false
	*/
	public function get(array $data)
	{
            $id = $data['id'];
            
            $selectQuery = "SELECT * FROM ".$this->_dbTable."";

            $dateInfo = Utils::dateInfo();

            $date = $dateInfo['date'];
            $time = $dateInfo['time'];

            $selectQuery .= " WHERE 1 = 1 ";
            $selectQuery .= " AND login_date = :date ";

            if ($id != "" && $id > 0) {
                    $selectQuery .=" AND user_id = :id";
            }

            try {		    

                $stmt = $this->_dbObject->dbh->prepare($selectQuery);

                $stmt->bindParam(':date', $date, \PDO::PARAM_STR); 

                if ($id != "" && $id > 0) {		    	
                    $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // bind to interger param only (as id is integer)
                }  

                if ($stmt->execute()) {		    	
                    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);	
                   
                        if (!empty($data)) {
                            return $data[0];
                        } else {
                            return false;   // no time data found, make first entry
                        }                            				
                } else {
                    return false; 
                }
                  
            } catch (Exception $e) {
                    // can log here the error in log file with $e->getCode and $e->getMessage
                    return false;
            }
    }
    
    public function saveUserTimings(array $data, array $dateInfo)
	{          
            $date       = $dateInfo['date'];
            $time       = $dateInfo['time'];
            $userId     = $data['id'];
            $isLate     = $data['isLate'];
            $lateByTime = $data['lateBy'];  
            
            $insertQuery = "INSERT INTO ".$this->_dbTable." "
                    .  "(user_id, login_date, login_time, is_late, late_by_time)" .""
                    . " VALUES(:userId, :loginDate, :loginTime, :isLate, :lateByTime)";
           
            try {		    

                $stmt = $this->_dbObject->dbh->prepare($insertQuery);

                $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);  
                $stmt->bindParam(':loginDate', $date, \PDO::PARAM_STR); 
                $stmt->bindParam(':loginTime', $time, \PDO::PARAM_STR);
                $stmt->bindParam(':isLate', $isLate, \PDO::PARAM_BOOL);
                $stmt->bindParam(':lateByTime', $lateByTime, \PDO::PARAM_STR);

                if ($stmt->execute()) {	
                    
                   $insertedId = $this->_dbObject->dbh->lastInsertId(); 
                   $returnData = array("id"         => $insertedId, 
                                       "loginDate"  => $date, 
                                       "loginTime"  => $time,
                                       "isLate"     => $isLate,
                                       "lateByTime" => $lateByTime,
                                      );
                   
                   return $returnData;
                   
                } else {
                    return false; 
                }
                  
            } catch (Exception $e) {                
                    // can log here the error in log file with $e->getCode and $e->getMessage
                    return false;
            }
    }

}
?>