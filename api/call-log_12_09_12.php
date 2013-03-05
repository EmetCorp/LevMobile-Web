<?php
// Include the Twilio PHP library
/*require 'Services/Twilio.php';*/
// Twilio REST API version
$version = '2010-04-01';
// Set our AccountSid and AuthToken
$sid = 'AC78689f5b95066c13cd60213da3901e99';
$token = 'e72f490c6e338466a46ec5c6ee2b295e';
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($sid, $token, $version);
try {
// Get Recent Calls
foreach ($client->account->calls as $call) {
/*echo "<br/>Call from $call->from to $call->to at $call->start_time of length $call->duration";*/
$msg[] = array("Message"=>"Success","CallFrom"=>$call->from ,"CallTo"=>$call->to,"StartTime"=>$call->start_time,"Duration"=>$call->duration);

}
} catch (Exception $e) {
	
	$msg[] = array("Message"=>"Error","ErrorMsg"=>$e->getMessage()); 
	/*echo 'Error: ' . $e->getMessage();*/
}
echo json_encode($msg);
?>