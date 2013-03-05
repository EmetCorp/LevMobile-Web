<?php
/*
 +------------------------------------------------------------------------+
 | PHP version 5.3.x                                                      |
 +------------------------------------------------------------------------+
 | Copyright (c) 2012-13 Classic Informatics                          	  |
 +------------------------------------------------------------------------+
 | Description:															  |
 | Classes to encapsulates, interprate Object created through Json & xml  |
 +------------------------------------------------------------------------+
 | Authors: @Sunil Malik :: Email - sunil.malik@classicinformatics.com 	  |
 +------------------------------------------------------------------------+
*/
require_once("../common/inc/global.inc.php");
require_once("../common/inc/config.php");
require_once("../classes/Email.php");
require_once("../library/Request.php");
require_once("../classes/Utility.php");
class Api
{
	public function __construct()     
	{
		
		$this->requestText = array('TWILIOAPI_LOGIN','TWILIOAPI_REGISTRATION','TWILIOAPI_FORGOTPASSWORD','TWILIOAPI_CALLLOG');
									   
		$this->requestType = @$_REQUEST['keyword'];
		$this->objEmail	   = new clsEmail();
		$this->objUtility  = new Utility();
	}

	//Process Request	
	public function processRequest()
	{
		if(!in_array($this->requestType, $this->requestText))
		{
			throw new Exception("Invalid Request Type: {$this->requestType}");
		}
		switch($this->requestType)
		{                       
			case 'TWILIOAPI_LOGIN' :  // User login authentication
          			return $this->loginUser();
          		break;
		/*	case 'TWILIOAPI_FORGOTPASSWORD' :  // User forgot password
          			return $this->forgotpass();
          		break;*/
			case 'TWILIOAPI_REGISTRATION' :  // User registration.
          			return $this->registration();
          		break;		
			case 'TWILIOAPI_CALLLOG' :
					return $this->callLog();
          		break;		
		   case 'LIVERYCAB_TEST' :  // Testing  
          			return $this->test();
          		break;		
		}
	}
//////////////////////////////////	
	/*
	* User Patrons Login for the both Android/Iphone
					*http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_LOGIN&mobileNo={mobileNo}&password=(PASS)&deviceId=(DEVICEID)&deviceToken={DEVICETOKEN}&deviceType={iphone}
	*/
	private function loginUser()
	{
		global $_objDB;	
		
		$mobileNo    = Request::data('mobileNo');
		$pass        = Request::data('password');
		$userType    = "P";
		$deviceId    = Request::data('deviceId');
		$deviceType  = strtolower(Request::data('deviceType'));
		$deviceToken = trim($_REQUEST['deviceToken']);
		$deviceToken = str_replace(array('<','>'),'',$deviceToken);
			
/*		if(!$this->validateUserNamePassword($email,$pass))
		{   
			$msg = array("Message"=>"valid_email_password","Status"=>"0");
		}
		else*/
		if($mobileNo!='' && $pass!='')
		{

				$result = $_objDB->ExecuteMultiQuery("SELECT id, mobileNo,
status FROM tblUsers WHERE mobileNo='".$mobileNo."' AND password='".base64_encode($pass)."'");
				$data = $_objDB ->FetchRecords();
				if(!empty($data))
				{
					if($data['0']['status']=='1')
					{
						$userId = $data['0']['id'];
	
					$msg = array("Message"=>"success","UserId"=>$userId,"UserName"=>$data['0']['mobileNo'],"Status"=>"ok");				
								
									
						if($deviceId!='' && $deviceType!='' && $deviceToken!='')
						{
							
							$query = " INSERT INTO tblDeviceTokens (userId, deviceId,deviceToken,deviceType) values('$userId','$deviceId','$deviceToken','$deviceType')  ON DUPLICATE KEY UPDATE deviceId='".$deviceId."',deviceToken='".$deviceToken."',deviceType='".$deviceType."'";
							$_objDB->ExecuteMultiQuery($query); 
					
						}
						else { $msg = array("Message"=>"missing_device_id_token_type","Status"=>"Failed"); }
					}
					else
					{
						$msg = array("Message"=>"Deactive","Status"=>"Failed");
					}
				}
				else
				{
					$msg = array("Message"=>"Invalid","Status"=>"Failed");
				}
					
		}
		 if(!isset($msg))
		 	$msg = array("Message"=>"Invalid","Status"=>"Failed");
		return(json_encode($msg));
	}
//////////////////////////////////	
/*
	Validation functions
*/
	protected function validateUserNamePassword($useremail,$password)
	{
		if($useremail=='' || $password=='')
			return false;
		else
		 	return true;	
	}

   /*
	* Forgot Password
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_FORGOTPASSWORD&username={EMAIL}					
	*/
private function forgotpass()
	{
		global $_objDB;
		global $_objDB,$site_url,$temp_site_url, $MAIL_TEAM_REGARDS;
		global $AdminEmail, $imageUrl, $imageTitle;
		
		$mobileNo    = Request::data('mobileNo');
							
		$_objDB->ExecuteMultiQuery("SELECT id, mobileNo status FROM tblUsers WHERE mobileNo='".$mobileNo."'");
			$emailDriver = $_objDB ->FetchRecords();
			if(!empty($emailDriver))
			{
				$to_mail 		= $emailDriver[0]['email'];
				$ForgetPassKey  = base64_decode($emailDriver[0]['password']);
				$firstName 		= $emailDriver[0]['firstName'];
				$lastName 		= $emailDriver[0]['lastName'];
				$userType 		= $emailDriver[0]['userType']; 
				
				
			$query = "SELECT header_id, subject, content FROM tblmailContents WHERE section='forgot_password' limit 0,1";
		   	$res = $_objDB ->ExecuteMultiQuery($query);
			$contentrec = $_objDB ->FetchRecords();
		   
		   	$header_id = $contentrec[0]["header_id"];
		   
		    $query = "SELECT header, footer FROM tblmailHeader WHERE id='".$header_id."' limit 0,1";
		    $res = $_objDB ->ExecuteMultiQuery($query);
			$headerrec = $_objDB ->FetchRecords();
		   
		   
		   	$message= html_entity_decode($headerrec[0]["header"], ENT_QUOTES).html_entity_decode($contentrec[0]["content"], ENT_QUOTES).html_entity_decode($headerrec[0]["footer"], ENT_QUOTES);
		   
		   
		    $mailSubject= $contentrec[0]["subject"];
			
			$message = str_replace("src=\"/", "src=\"".$temp_site_url."/", $message);
			$message = ereg_replace('\~\~NAME\~\~', stripsl($firstName." ".$lastName), $message);
			$message = ereg_replace('\~\~USERNAME\~\~', $email, $message);			
			$message = ereg_replace('\~\~PASSWORD\~\~', $ForgetPassKey, $message);
			$message = ereg_replace('\~\~imageTitle\~\~', $imageTitle, $message);	
			$message = ereg_replace('\~\~MAIL_TEAM_REGARDS\~\~',$MAIL_TEAM_REGARDS, $message);
		  	 $mail_to = $email;	
		   	/*$mail_to = "sunil.malik@classicinformatics.com";*/	   		   
		 	  $this->objEmail->CommonMail("",$mail_to,$mailSubject,$message);

				$msg = array("Message"=>"check_mail","Status"=>"Ok");
			}
			else
			{
				$msg = array("Message"=>"invalid_email","Status"=>"Failed");
			}
		
		return(json_encode($msg));
	}
	
	/*
	* User Patrons registration for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_REGISTRATION&mobileNo={mobileNo}&email={EMAIL}&password={PASSWORD}&address={address}&deviceId=(DEVICEID)&deviceToken={DEVICETOKEN}&deviceType={iphone}				
	*/
  private function registration()
	{
		global $_objDB;
		global $_objDB,$site_url,$temp_site_url, $MAIL_TEAM_REGARDS;
		global $AdminEmail, $imageUrl, $imageTitle;
		
		$mobileNo      		= trim(Request::data('mobileNo'));
		$email	       		= trim(Request::data('email'));
		$address      		= trim(Request::data('address'));
		$password			= base64_encode(Request::data('password'));
		$deviceId   		= Request::data('deviceId');
		$deviceType 		= strtolower(Request::data('deviceType'));
		$deviceToken		= trim($_REQUEST['deviceToken']);
			
		if($mobileNo!='')
		{
			$checkUser = $this->DuplicateUser($mobileNo);
			$checkEmail = $this->DuplicateEmail($email);
			if(!empty($checkUser))
			{
				$msg = array("Message"=>"mobile_already_in_use","Status"=>"Failed");
			}
		   elseif(!empty($checkEmail))
			{
				$msg = array("Message"=>"email_already_in_use","Status"=>"Failed");
			}
			else
			{
			
				$_objDB ->ExecuteMultiQuery("INSERT INTO tblUsers SET mobileNo='".$mobileNo."' , email='".$email."', password='".$password."', address='".$address."', dateAdded=now()");	
				
				
				$LastId = $_objDB->InsertedID();
				
					
				if($LastId!='' && $deviceId!='' && $deviceType!='' && $deviceToken!='')
				{
					$query = " INSERT INTO tblDeviceTokens (userId, deviceId,deviceToken,deviceType) values('".$LastId."','$deviceId','$deviceToken','$deviceType')  ON DUPLICATE KEY UPDATE deviceId='".$deviceId."',deviceToken='".$deviceToken."',deviceType='".$deviceType."'";
				
					$_objDB->ExecuteMultiQuery($query); 
				}
				
				$query = "SELECT header_id, subject, content FROM tblmailContents WHERE section='registration' limit 0,1";
		   	$res = $_objDB ->ExecuteMultiQuery($query);
			$contentrec = $_objDB ->FetchRecords();
		   
		   	$header_id = $contentrec[0]["header_id"];
		   
		    $query = "SELECT header, footer FROM tblmailHeader WHERE id='".$header_id."' limit 0,1";
		    $res = $_objDB ->ExecuteMultiQuery($query);
			$headerrec = $_objDB ->FetchRecords();
		   
		   
		   	$message= html_entity_decode($headerrec[0]["header"], ENT_QUOTES).html_entity_decode($contentrec[0]["content"], ENT_QUOTES).html_entity_decode($headerrec[0]["footer"], ENT_QUOTES);
		   
		   
		    $mailSubject= $contentrec[0]["subject"];

			$message = str_replace("src=\"/", "src=\"".$temp_site_url."/", $message);
	/*		$message = ereg_replace('\~\~NAME\~\~', stripsl($firstName." ".$lastName), $message);*/	
			$message = ereg_replace('\~\~NAME\~\~', $email, $message);		
			$message = ereg_replace('\~\~USERNAME\~\~', $mobileNo, $message);		
			$message = ereg_replace('\~\~PASSWORD\~\~', base64_decode($password), $message);
			$message = ereg_replace('\~\~imageTitle\~\~', $imageTitle, $message);	
			$message = ereg_replace('\~\~MAIL_TEAM_REGARDS\~\~',$MAIL_TEAM_REGARDS, $message);
		   	$mail_to = $email;
			/*$mail_to = "sunil.malik@classicinformatics.com";*/
			$this->objEmail->CommonMail("",$mail_to,$mailSubject,$message);
				$msg = array("Message"=>"Registered_Successfully","Status"=>"Ok","UserId"=>$LastId,"mobileNo"=>$mobileNo,"email"=>$email);
			}
		} 
		else
		{
			$msg = array("Message"=>"invalid_email","Status"=>"Failed");
		}
		return(json_encode($msg));

	}

	/*
	* User Patrons registration for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_CALLLOG				
	*/
  private function callLog()
	{
		global $_objDB;	
		include("call-log.php");
	}
	
	protected function DuplicateUser($mobileNo)
	{
		global $_objDB;
		$Duplicateusr = $_objDB ->ExecuteMultiQuery("SELECT mobileNo FROM tblUsers WHERE mobileNo='".$mobileNo."'");
		$Duplicateusr = $_objDB ->FetchRecords();
		$array          = @$Duplicateusr['0']['mobileNo'];
		return $array;
	}	
	
	protected function DuplicateEmail($email)
	{
		global $_objDB;
		$Duplicateusr = $_objDB ->ExecuteMultiQuery("SELECT email FROM tblUsers WHERE email='".$email."'");
		$Duplicateusr = $_objDB ->FetchRecords();
		$array          = @$Duplicateusr['0']['email'];
		return $array;
	}	
	
	
	public function getErrorJson($errorText)
	{
		$xmlText = json_encode(array("Message"=>"{ERROR}","Status"=>"Failed"));;
		return str_replace('{ERROR}',$errorText, $xmlText);
	}
}

$objApi = new Api();

try
{
	echo $objApi->processRequest();
}
catch(Exception $e)
{
	echo $objApi->getErrorJson($e->getMessage());
}
?>