<?php
namespace Weblab\Lib;

use Weblab\Controllers;

class CreateResource
{
	public static function getInstance(DB $db, $className = "")
	{
		if ($className != "") {

			$realClassName = ucfirst($className).'Controller';

			try {
                                $actualClass = '\\Weblab\Controllers\\'.$realClassName;
                                
				$obj = new  $actualClass($db);

				return $obj;
			} catch (Excepetion $e) {
				return false;
			}

		}

		return false;
	}

}
?>
