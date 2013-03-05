<?php
require ($_SERVER['DOCUMENT_ROOT']."/api/Services/Twilio.php");
class sms
{	
	public function __construct()
	{
				
	}
	public static function sendSms($fromNo,$toNo,$msg)
	{		
		$AccountSid = "AC78689f5b95066c13cd60213da3901e99";
		$AuthToken = "e72f490c6e338466a46ec5c6ee2b295e";
		$client = new Services_Twilio($AccountSid, $AuthToken);
		$client->account->sms_messages->create('+'.$fromNo, '+'.$toNo, $msg);
	}
}
?>