<?php
namespace Weblab\UnitTest;

use Weblab\Lib;
use Weblab\Lib\Utils as Utils;

/**
 * @covers users get data
 */
final class Custom_UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNoFilters()
    {
        $userName = "";
        
        $selectQuery = "SELECT * FROM system_users";

        $dateInfo = Utils::dateInfo();

        $date = $dateInfo['date'];
        $time = $dateInfo['time'];

        $selectQuery .= " WHERE 1 = 1 ";

        if ($userName != "") {
                $selectQuery .=" AND username = :userName ";
        }
            
        $db = \Weblab\Lib\DB::getInstance();
        $stmt = $db->dbh->prepare($selectQuery);

        $data = array();
        $total = 0;
        if ($userName != "") {		    	
            $stmt->bindParam(':userName', $userName, \PDO::PARAM_STR); // bind to string param only (as id is integer)
        }  

        if ($stmt->execute()) {		    	
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);	
            if ($data && count($data)) {
                $total = count($data);
                $this->assertTrue($total > 0);
            }
        }    

        if ($total <= 0) {
            $this->assertTrue($total < 0);
        }
        
    }

    public function testWithUserName()
    {

    }

    public function testWithLoginDate()
    {

    }
}

