<?php
/*
*Global file to include on every php file.
*/
require_once('variables.php');
ini_set('display_errors', 1);
//ini_set("memory_limit", "512M");
 /*
if(_CFG_DEBUG)						// application in debug mode
	error_reporting(E_ALL);			// show all notification
else
	error_reporting(E_ERROR);		// show only arror*/

//session_start();

require_once(_LIB_PATH . "/" . _CFG_DB_CLASS_FILE); // add database class files
require_once(_LIB_PATH . '/Request.php');			// include general request class file
require_once(_CLS_PATH . '/Utility.php');			// Include General Utility functions providing Class

try
{

	$_objDB = new clsDADB(_CFG_DB_HOST,_CHG_DB_USER,_CFG_DB_PASS,_CFG_DB_DATABASE);
	$_objDB->connect();
}
catch (Exception $e)
{
	echo '<h1>Database Maintenance</h1>'.
			'The database is presently undergoing emergency maintenance and will be down '.
			'for a few more minutes.  We apologize for the delay.'.
			'<br><br>If the maintenance is taking too long, or you feel there may '.
			'be an immediate problem, please contact the webmaster.';
	exit;
}