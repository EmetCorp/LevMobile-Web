<?php
/*_______________________________________________________________________
Created By	: Sunil Malik
Created On	: 19-April-2012
Modified By :
Modified On :
Description : This file consists all the config varibales and settings used in the site
_________________________________________________________________________
*/
date_default_timezone_set('UTC');
session_start();
if (!defined("_CFG_DB_CLASS_FILE"))
	define("_CFG_DB_CLASS_FILE", "MySql.php");		// database provider class file
if (!defined("_CFG_DB_HOST"))
	define("_CFG_DB_HOST", "localhost");			// database host
if (!defined("_CHG_DB_USER"))
	define("_CHG_DB_USER", "levmobile"); 				// Database user
if (!defined("_CFG_DB_PASS"))
	define("_CFG_DB_PASS", "mobilelev2132!");		// Database Password
if (!defined("_CFG_DB_DATABASE"))
	define("_CFG_DB_DATABASE", "levmobile"); 			// Database name
	
define("DOCUMENTROOT",$_SERVER['DOCUMENT_ROOT']);
$site_url="http://".$_SERVER['HTTP_HOST'];
$secure_url="http://".$_SERVER['HTTP_HOST'];
define("SITE_URL",$site_url);
define("SECURE_URL",$secure_url);
define("INC",DOCUMENTROOT."/common/inc/");
define("CLASSES",DOCUMENTROOT."common/classes/");
define("IMAGE_PATH", SITE_URL."images/");
$imageTitle = "Twilio App";

if (!defined("_SUPPORT_EMAIL"))
        define("_SUPPORT_EMAIL", "info@levmobile.com");  
		
if(get_magic_quotes_gpc())
 	define('MAGIC_QUOTE_ENABLED',true);
else define('MAGIC_QUOTE_ENABLED',false);
?>