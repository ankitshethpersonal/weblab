<?php
/* @File : to define the global- site wide access parameters */
DEFINE('DS', DIRECTORY_SEPARATOR);

// define the base path of the project folder - give folder name after the $_SERVER['DOCUMENT_ROOT']
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'].DS."test".DS."restapi");

// define the db info
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root123');
define('DB_DATABASE', 'weblab');
define('DB_PORT', '3306');

define('DEFAULT_LOGIN_TIME', "10:30"); // define the max entry valid time
define('USERS_PER_PAGE',10); // pagination of users data

define('API_KEY','web222323jsk33dtest123');
define('APP_SECRET','abc0619430362ab7d8web2lca0b63412112771141a56282');
define('TOKEN_LIFE_TIME',600000);
define('JWT_ALGORITHM','HS256');

?>
