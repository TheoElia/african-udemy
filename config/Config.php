<?php 
//error_reporting(E_ALL); ini_set('display_errors', 1);
//---------------------
// Session Start
//---------------------
//has to be first output on each page
if(!isset($_SESSION)) {
  session_start();
}




//----------------------------------------------
// Server/Host Name & Environment detection
//----------------------------------------------
$env = "";
$server_name = isset($_SERVER['HOSTNAME'])? $_SERVER['HOSTNAME'] : $_SERVER['SERVER_NAME'];

if(strtolower($server_name) == 'localhost' || strtolower($server_name) == '127.0.0.1' || strtolower($server_name) == 'african-udemy.com'){
	$env = "development";
}
//QA
else if(strtolower($server_name) == '66.205.176.237'){
	$env = "test";
}






//--------------
// base url
//--------------
$base_url;

if(empty($_SERVER['HTTPS'])) {
    $base_url = 'http://' . $server_name . '/african-udemy';
}else{
	$base_url = 'https://' . $server_name . '/african-udemy';
}






//------------------------------
//system wide timezone setting
//------------------------------
if($env == 'development'){
	date_default_timezone_set('UTC');
}
//QA
else if($env == 'test'){
	date_default_timezone_set('America/Chicago');
}






//---------------------
//Error handling
//---------------------
//Development
if($env == 'development'){
	error_reporting(E_ALL); 
	ini_set('display_errors', 1);
	ini_set('log_errors' , 1);
	ini_set('error_log', __DIR__ . '/../logs/php-error.log');
	// error_log("Logs loaded for voting-tool");
}
//QA
else if($env == 'test'){
	error_reporting(0); 
	ini_set('display_errors', 0);
	ini_set('log_errors' , 1);
	ini_set('error_log', __DIR__ . '/../logs/php-error.log');
	// error_log("Logs for voting-tool");
}





//--------------------------
//database settings
//-------------------------
define('DB_HOST', 'localhost');

//Development & QA DB settings

if($env == 'development' || $env == 'test'){
	define('DB_USER', 'dev_stsuser0');
	define('DB_NAME', 'syncline_ts_dev');
	define('DB_PASS', 'td4A@8UYPBNK4ad4!3');
}





//**********************************************
// GENERAL SYSTEM PARAMS/SETTINGS
//**********************************************
// 
// directory
define( "API_DIR", __DIR__ . "/../api/" );
define( "PROJECT_DIR", __DIR__ . "/../" );

// files
define( "AUTOLOAD", PROJECT_DIR . "vendor/autoload.php" );
define( "DB_CONNECT", PROJECT_DIR . "inc/db_connect.php" );




// api responses
$client_messages = array(
	// informational
	1001 => ["user not registered", 404],
	1002 => ["already exists", 409],
	1003 => ["action unavailable", 503],
	1004 => ["session expired", 401],
	1005 => ["does not exist", 404],
	1006 => ["no record(s) found", 200],
	1007 => ["invalid inputs", 400],
	1008 => ["user not authenticated", 401],
	1009 => ["user not authorised", 401],
	1010 => ["nothing changed", 200],
	1011 => ["not allowed", 403],
	// success
	2001 => ["registration successful", 201],
	2002 => ["login successful", 200],
	2003 => ["logout successful", 200],
	2004 => ["updated successfully", 200],
	2005 => ["reset successful", 200],
	2006 => ["saved successfully", 201],
	2007 => ["deleted successfully", 200],
	2008 => ["record(s) retrieved", 200],
	2009 => ["restore successful", 200],
	// client error
	4001 => ["invalid email", 400],
	4002 => ["duplicate email", 409],
	4003 => ["username or password is incorrect", 401],
	4004 => ["invalid token", 400],
	4005 => ["missing paramater(s) or incorrect data format", 400],
	4006 => ["user does not exist", 404],
	4007 => ["inputs must not match", 409],
	4008 => ["error logging in from iMIS", 407],
	4009 => ["invalid/disabled user account from iMIS", 423],
	// server error
	5001 => ["error saving data", 500],
	5002 => ["error retrieving data", 500],
	5003 => ["error deleting data", 500],
	5004 => ["error updating data", 500],
	5005 => ["error logging in", 500],
	5006 => ["error logging out", 500],
	// custom 
	6006 => ["Invalid First Name", 400],
	6007 => ["invalid offsets", 400],
	6010 => ["invalid date", 400],
	6011 => ["Invalid Last Name", 400],
	6012 => ["You are not allowed Add a Super-Admin", 400],
	6013 => ["Invalid lenth of characters"],
	6014 => ["No Valid ID Specified"],
	6015 => ["Not Qualified to edit Super - Admin", 400],
	6016 => ["Not Qualified to delete Super - Admin", 400],
	6017 => ["Old password is the same as new password", 400],
	6021 =>	["No route has been assigned to this trip", 404],
	6018 => ["No trip price has been assigned for this bus ", 404],
	6019 => ["No bus number plate has been assigned to this bus", 404],
	6020 => ["There was a duplicate trip shedule and operation has been aborted", 409]
	
);


// param data types
$param_types = array(
	// common
	'id' => 'integer',
	'created_by' => 'integer',
	'updated_by' => 'integer',
	'name' => 'string',
	'users' => 'array',
	'user_id' => 'integer',
	'status' => 'integer',
	'module_id' => 'integer',
	'module' => 'string',
	'offset' => 'integer',
	'limit' => 'integer',
	'last_id' => 'integer',
	// user
	'first_name' => 'string',
	'middle_name' => 'string',
	'last_name' => 'string',
	'username' => 'string',
	'email' => 'string',
	'is_admin' => 'integer',
	// auth
	'username' => 'string',
	'password' => 'string',
);


 ?>
