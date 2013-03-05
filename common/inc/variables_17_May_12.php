<?php
/*_______________________________________________________________________
Created By    : Sunil Malik
Created On    : 24-Oct-2011
Modified By :
Modified On :
Description : define global variables
_________________________________________________________________________
*/

//include cofig file
include_once('config.php');
include_once(INC."functions.php");/**/

if(!isset($include_session))
    $include_session = 1;

$MAIL_TEAM_REGARDS = "Twilio Application";
/****************  Following code will be for the Cross Site Scripting Starts **************/
//Extract data
if (!empty($_POST)) {
	reset($_POST);
	while (list($k,$v)=each($_POST)) {
		if(!is_array($_POST{$k}))
		{
			$val=str_replace("&amp;","&",htmlentities($v,ENT_QUOTES));
			$$k=$val;
			$_POST{$k}=$val;
		}
	}
}
if (!empty($_GET)) {
	reset($_GET);
	while (list($k,$v)=each($_GET))
	{
		if(!is_array($_GET{$k}))
		{
			$val=str_replace("&amp;","&",htmlentities($v,ENT_QUOTES));
			$$k=$val;
			$_GET{$k}=$val;
		}
	}
}

/****************  Following code will be for the Cross Site Scripting ends **************/

$page_name=basename($_SERVER["PHP_SELF"]);
@$imageUrl=IMAGE_PATH; // images URL


/* ---------------  Array Section Start--------------------------------------------*/

@$tdrow = 0;
/* Feature Video Path*/
$admin_ext='../';
	






if (!defined("_WWWROOT"))
        define("_WWWROOT", "http://" . @$_SERVER['SERVER_NAME']);
// local path of web root
if (!defined("_PATH"))
		define("_PATH", $_SERVER['DOCUMENT_ROOT']);		
	// local path of web lib
if (!defined("_LIB_PATH"))
        define("_LIB_PATH", _PATH .'/library');	
// local path of application classes
if (!defined("_CLS_PATH"))
        define("_CLS_PATH", _PATH .'/classes');		
		
//added by Sunil Malik for secure url
$secUrl= str_ireplace('http:', 'https:', $site_url);

$temp_site_url = SITE_URL;

?>