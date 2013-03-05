<?php
/*
	Code for Outgoing calls
*/
include "Services/Twilio/Capability.php";
// AccountSid and AuthToken can be found in your account dashboard

$accountSid = "AC78689f5b95066c13cd60213da3901e99";
$authToken = "e72f490c6e338466a46ec5c6ee2b295e";
// The app outgoing connections will use:
//$appSid = "APabe7650f654fc34655fc81ae71caa3ff";


$appSid = "AP576d73d5f10e4356bd75ba76ade08725";

// The client name for incoming connections:
$clientName = "monkey";
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
?>