<?php
/*
*Code for Receiving Incoming Connections
*http://www.twilio.com/docs/quickstart/php/ios-client/incoming-connections
*/
require_once("../common/inc/global.inc.php");
require_once("../common/inc/config.php");
require_once("../classes/Utility.php");
include "Services/Twilio/Capability.php";
require ($_SERVER['DOCUMENT_ROOT']."/api/Services/Twilio.php");

$userId 	= "";
if(isset($_REQUEST["userId"]) && is_numeric($_REQUEST["userId"]))
{
	$userId 	= $_REQUEST["userId"];
	$pstrcond	= "id=".$userId;
	$usrRow	= Utility::getTableData("tblUsers",$pstrcond,"subaccountSid, subaccountAuthToken, twimlapplication,userType, firstName");
}

$accountSid = "";
$authToken = ""; 
if(isset($usrRow[0]["subaccountSid"])) $accountSid = $usrRow[0]["subaccountSid"];
if(isset($usrRow[0]["subaccountAuthToken"])) $authToken = $usrRow[0]["subaccountAuthToken"];
if(isset($usrRow[0]["userType"])) $userType = $usrRow[0]["userType"];	

if(!empty($accountSid) && !empty($authToken))
  {
	   
	if(!empty($usrRow[0]["twimlapplication"])) $appSid = $usrRow[0]["twimlapplication"];
	if($userType == 'P')
	{
		// The client name for incoming connections:
		$clientName =  $_REQUEST["clientName"];
		$capability = new Services_Twilio_Capability($accountSid, $authToken);
		// This allows incoming connections as $clientName:
		$capability->allowClientIncoming($clientName);
		// This allows outgoing connections to $appSid with the "From"
		// parameter being the value of $clientName
		$capability->allowClientOutgoing($appSid, array(), $clientName);
		// This returns a token to use with Twilio based on
		// the account and capabilities defined above
		$token = $capability->generateToken(86400);
		echo $token;
		//$msg = array("Message"=>"sucessfully_call_established","Status"=>"Ok");
	}
	else
	{
		$mesg = userLogCallCount($accountSid,$authToken); 
		if($mesg <= 3)
		{
			// The client name for incoming connections:
			$clientName =  $_REQUEST["clientName"];
			$capability = new Services_Twilio_Capability($accountSid, $authToken);
			// This allows incoming connections as $clientName:
			$capability->allowClientIncoming($clientName);
			// This allows outgoing connections to $appSid with the "From"
			// parameter being the value of $clientName
			$capability->allowClientOutgoing($appSid, array(), $clientName);
			// This returns a token to use with Twilio based on
			// the account and capabilities defined above
			$token = $capability->generateToken(86400);
			echo $token;
			//$msg = array("Message"=>"sucessfully_call_established","Status"=>"Ok");
		}
		else
		{
			echo "your_account_not_paid_account";
			//$msg = array("Message"=>"your_account_not_paid_account","Status"=>"Ok");
			//echo json_encode($msg);
		}
	}	
  }
else
  echo "";
  
  /**Get user sms log count*/
function userLogCallCount($AccountSid,$AuthToken)
{
	$ApiVersion = "2010-04-01";	
	$count = 0;
	try
	{
		$client = new Services_Twilio($AccountSid, $AuthToken, $ApiVersion);
		$mesg[] = ""; 
		foreach($client->account->calls->getIterator(0, 50, array("Status" => "completed")) as $mssage)
		{
			$mesg[] = $mssage->status;
		}
		$count = count($mesg);
	}
	catch (Exception $e) 
	{
		$count = 0;
	}
	return $count;
}
?>