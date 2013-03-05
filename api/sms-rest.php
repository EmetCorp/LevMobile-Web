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
		
	$fromId 	= 0;
	if(isset($_REQUEST["fromId"]) && is_numeric($_REQUEST["fromId"]))
	{
		$fromId 	= $_REQUEST["fromId"];
		$pstrcond	= "id=".$fromId;
		$usrRow	= Utility::getTableData("tblUsers",$pstrcond,"subaccountSid, subaccountAuthToken,userType");
	}
	//echo "<pre>"; print_r($usrRow); die;
	
	$AccountSid = "";
	$AuthToken = ""; 
	$msg 	=	"";
	if(isset($usrRow[0]["subaccountSid"]))	$AccountSid = $usrRow[0]["subaccountSid"];
	if(isset($usrRow[0]["subaccountAuthToken"])) $AuthToken = $usrRow[0]["subaccountAuthToken"];
	if(isset($usrRow[0]["userType"])) $userType = $usrRow[0]["userType"];		
	
	if(isset($_REQUEST["fromNo"])) $fromNo   = trim($_REQUEST["fromNo"]);
	if(isset($_REQUEST["toNo"])) $toNo       = trim($_REQUEST["toNo"]);
	if(isset($_REQUEST["msg"]))	$msg 	     = trim($_REQUEST["msg"]);
   
	$userMobile = $Objfrontenduser->CheckTwilioUser($toNo); 
	//Send message to LevMobile user  to LevMobile user
	if(@$userMobile[0]["mobileNo"] == $_REQUEST["toNo"])
	{
	   if(isset($_REQUEST['fromId'])) $fromId = $_REQUEST['fromId'];
	   $Objfrontenduser->addTextMsg($userMobile[0]["id"],$msg, $fromId);
	   $msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok"); 	
	}
	else
	{
		//Send message to LevMobile user  to other user  
		try
		{ 
			$client = new Services_Twilio($AccountSid, $AuthToken, $ApiVersion);
			if($userType == 'P')
			{
				$client->account->sms_messages->create('+'.$fromNo, '+'.$toNo, $msg);
				$msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok");
			}
			else
			{
				$mesg = "";
				foreach($client->account->sms_messages->getIterator(0, 50, array("Status" => "sent")) as $mssage)
				{
					$mesg[] = $mssage->status;
				}
				if(count($mesg) <= 3)
				{
					$client->account->sms_messages->create('+'.$fromNo, '+'.$toNo, $msg);
					$msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok"); 	
				}
				else
				{
					$msg = array("Message"=>"your_account_not_paid_account","Status"=>"Failed"); 
				}
			}
		}		
		catch (Exception $e) 
		{
			$err = $e->getMessage();
			$msg =array("Message"=>$err,"Status"=>"Failed"); 
		}
	}
	 
	echo json_encode($msg);  
?>