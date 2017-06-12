/* File to get the general info of the project */

Please set the info of PATH and DB in Lib/config.php file

Sql - folder contain the sql file - dummy data are there.. : weblab.sql)

Also, make sure, mod_rewrite is enable in your server


--Created Rest APIs with custom mvc 
--Tried to follow design patterns (dependency injection, factory, singleton)
--Tried to follow proper coding conventions
-- Tried to follow composer and namespaces
-- Write one test case (can surely write more.. sorry for not getting enough time at my side).

API Test :
-- Pre-requisites:
-- Add username in db - system_users table
-- Then, call below api to register user timings in system_user_timings table
   Method : Post  
   Endpoint: http://localhost/test/restapi/index.php?controller=user&method=post   
   Post Json : {"userName":"ankit.sheth"} // Or your entered userName in db (manually)
-- Above api automatically login your time to db for current date
-- If you call above api again, it gives you warning message
-- If your late then also it gives you warning message
-- It gives you flags ({data:{isLate:1/0}}) so based on that we can display the above message


Admin Apis :
1] To get all users :

Method : GET
EndPoint: http://localhost/test/restapi/index.php?controller=admin&method=users

2] Selected user data:
If pass userName="USERNAME", i.e. userName=ankit,
it gives data with only users whose names starts with ankit.
EndPoint: http://localhost/test/restapi/index.php?controller=admin&method=users&userName=ankit

You can also pass loginDate=Y-M-D format , i.e. loginDate=2017-06-11

3] To get team average time
Method : GET
Endpoint: http://localhost/test/restapi/index.php?controller=admin&method=avgtime

To get user related endpoint :
Endpoint: http://localhost/test/restapi/index.php?controller=admin&method=avgtime&userName=ankit

4]To get user analytics :
http://localhost/test/restapi/index.php?controller=admin&method=analytics&userName=ankit

Note : Here i can do below things if get more time :
-- Can user proper more validations and can also use Error Class (common and also monolog, cloudwatch)
-- Can also use proper angular js (controller(to parse apis), services(to common call of apis), factory : to create singleton object)
-- Can also use more proper oops and validations, can you Trait to define common methods
-- Also can create proper admin user with jwttoken (already write the functios in AuthService for the same), with password validation(create salt and use in db, to only encrypt both side).

Sorry for not completing it 100% because of time constraints (actually my time constraints, otherwise you have provided enough time).
But tried from my side to make it proper so can enhance it in future easy way and can create new controllers/services easily.

Kindly provide your valuable feedback !! Really it means for me to enhance/know more !!






