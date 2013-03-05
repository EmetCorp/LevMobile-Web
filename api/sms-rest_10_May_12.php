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

	// Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
	$AccountSid = "AC78689f5b95066c13cd60213da3901e99";
	$AuthToken = "e72f490c6e338466a46ec5c6ee2b295e";

	// Step 3: instantiate a new Twilio Rest Client
	$client = new Services_Twilio($AccountSid, $AuthToken);

	// Step 4: make an array of people we know, to send them a message. 
	// Feel free to change/add your own phone number and name here.
/*	$people = array(
		"+14158675309" => "Curious George",
		"+14158675310" => "Boots",
		"+14158675311" => "Virgil",
	);*/
	
	/*$people = array(
		"+19084873577" => "Testing Client",	
		"+16465536390" => "Testing Client",
	);*/	
	 
	 if(isset($_REQUEST["fromNo"])) 
   	   $fromNo   = $_REQUEST["fromNo"];
	   
	 if(isset($_REQUEST["toNo"])) 
   	   $toNo   = $_REQUEST["toNo"];
	  
		$msg 	=	"";
	
  if(isset($_REQUEST["msg"]))
	$msg 	=	$_REQUEST["msg"];
		
	// Step 5: Loop over all our friends. $number is a phone number above, and 
	// $name is the name next to it

		$sms = $client->account->sms_messages->create(

		// Step 6: Change the 'From' number below to be a valid Twilio number 
		// that you've purchased, or the Sandbox number
			//"YYY-YYY-YYYY"
			//"+16465536390",
			  $fromNo,    

			// the number we are sending to - Any phone number
			$toNo,

			// the sms body
			$msg
			//"Hey $name, Monkey Party at 6PM. Bring Bananas!"
		);

		// Display a confirmation message on the screen
		$msg = array("Message"=>"Sent message to $toNo","Status"=>"Ok"); 
		echo json_encode($msg);

