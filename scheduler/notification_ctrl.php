<?php
// push notification settings for IPhone
if(!defined("_APN_HOST")) define("_APN_HOST",'gateway.sandbox.push.apple.com');
if(!defined("_APN_PORT"))	define("_APN_PORT",2195);
if(!defined("_APN_CERTIFICATE_PATH"))	define("_APN_CERTIFICATE_PATH",$_SERVER['DOCUMENT_ROOT']."/certificate/server_cerificates_bundle_sandbox.pem");

class pushNotification
{
	private $apnsHost = _APN_HOST;
	private $apnsPort = _APN_PORT;
	private $apnsCert = _APN_CERTIFICATE_PATH;
	private $apns;
	
	public function pushMsg($payload,$deviceToken,$deviceType)
	{
		$deviceType = strtolower(trim($deviceType));		
		if($deviceType=='iphone')
		{
		  $this->generateApns();
		  $this->IphoneSendMessageToPhone($payload,$deviceToken);
		}
	}
	
	/*send push notification to user for voice message*/
	public function sendPushNotiForVoicemsg($dToken,$dType,$name)
	{
		$payload 		= array();
		$deviceToken	= $dToken;		
		$deviceType		= $dType;		
		$msg 			= $name.' just sent you a new voice message.';	
		$payload['aps'] = array('alert' => $msg, 'badge' => 1, 'sound' => 'default');
		$this->pushMsg($payload,$deviceToken,$deviceType);		
		return true;
	}
	
    /*create APNs for Iphone*/
	public function generateApns()
	{
		$error='';
		$streamContext = stream_context_create();
		stream_context_set_option($streamContext, 'ssl', 'local_cert', $this->apnsCert);
		$this->apns = stream_socket_client('ssl://'.$this->apnsHost.':' .$this->apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
		if($error || empty($this->apns))
		  print("Error in connection with APNS server (No. -".$error.")!!");
	}
		
	/*send message for iphone*/
	public function IphoneSendMessageToPhone($payload,$deviceToken) 
	{		
		if(empty($payload) || empty($deviceToken) || ($deviceToken=='(null)'))
			return false;
		$payload = json_encode($payload);			
		$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
		fwrite($this->apns, $apnsMessage);
		fclose($this->apns);
	}
}
?>
