<?php
	/* Send an SMS using Twilio. You can run this file 3 different ways:
	 *
	 * - Save it as sendnotifications.php and at the command line, run 
	 *        php sendnotifications.php
	 *
	 * - Upload it to a web host and load mywebhost.com/sendnotifications.php 
	 *   in a web browser.
	 * - Download a local server like WAMP, MAMP or XAMPP. Point the web root 
	 *   directory to the folder containing this file, and load 
	 *   localhost:8888/sendnotifications.php in a web browser.
	 */
	require_once("../common/inc/global.inc.php");
	require ($_SERVER['DOCUMENT_ROOT']."/api/Services/Twilio.php");
	require_once("../classes/frontenduser.php");	
	
	$Objfrontenduser = new frontenduser();
	
	$ApiVersion = "2010-04-01";	
	$AccountSid = "AC78689f5b95066c13cd60213da3901e99";
	$AuthToken  = "e72f490c6e338466a46ec5c6ee2b295e";
	
	if(isset($_REQUEST["fromNo"])) $fromNo   = trim($_REQUEST["fromNo"]);
	if(isset($_REQUEST["toNo"])) $toNo       = trim($_REQUEST["toNo"]);
	$msg 	=	"";
	if(isset($_REQUEST["msg"]))	$msg 	     = trim($_REQUEST["msg"]);

	$fromId = 0;
	
	$userMobile = $Objfrontenduser->CheckTwilioUser($toNo); 
	if(@$userMobile[0]["mobileNo"] == $_REQUEST["toNo"])
	{
	   if(isset($_REQUEST['fromId']))
	   		$fromId	= $_REQUEST['fromId'];
		
		$Objfrontenduser->addTextMsg($userMobile[0]["id"],$msg, $fromId);
		$msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok"); 	
	}
	else
	{
		
		$client = new Services_Twilio($AccountSid, $AuthToken, $ApiVersion);	  	
		 
		try
		{
			$client->account->sms_messages->create('+'.$fromNo, '+'.$toNo, $msg);
			$msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok"); 		
		}
		catch (Exception $e) 
		{
			$err = $e->getMessage();
			$msg =array("Message"=>$err,"Status"=>"Failed"); 
		}
	}
	 
	echo json_encode($msg);  
		  
?>