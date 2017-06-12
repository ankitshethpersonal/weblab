<?php
namespace Weblab\Lib;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserContainer
{
    /* @Function : to get the instance with all depedencies
     * 
     */
    public static function getInstance() {
 
        $db = DB::getInstance();

        $objUser = new \Weblab\Services\Custom_User();
        $objUser->setDB($db);
        
        return $objUser;
    }    
    
}
