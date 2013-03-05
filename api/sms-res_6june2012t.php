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

	// Step 1: Download the Twilio-PHP library from twilio.com/docs/libraries, 
	// and move it into the folder containing this file.
	require "Services/Twilio.php";

	//$ApiVersion = "2008-08-01";
	$ApiVersion = "2010-04-01";
	// Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
	$AccountSid = "AC78689f5b95066c13cd60213da3901e99";
	$AuthToken = "e72f490c6e338466a46ec5c6ee2b295e";

 	if(isset($_REQUEST["fromNo"])) 
   	   $fromNo   = $_REQUEST["fromNo"];
	   
	 if(isset($_REQUEST["toNo"])) 
   	   $toNo   = $_REQUEST["toNo"];
	  
		$msg 	=	"";
	
  	if(isset($_REQUEST["msg"]))
		$msg 	=	$_REQUEST["msg"];


	// Message Value
	
	/* $data = array(
	  "From"=> $fromNo,
	  "To"=>  $toNo,
	  "Body"=> $msg
	  );*/
	  
	  //$client = new TwilioRestClient($AccountSid, $AuthToken);
	  $client = new Services_Twilio($AccountSid, $AuthToken, $ApiVersion);
	  	  
		try
		{
			$client->account->sms_messages->create($fromNo, $toNo, $msg);
			$msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok"); 
		
		}
		catch (Exception $e) 
		{
			//echo 'Error: ' . $e->getMessage();
			$err = $e->getMessage();
			array("Message"=>$err,"Status"=>"Failed"); 
		}  
		  echo json_encode($msg);
		  
		  
?>