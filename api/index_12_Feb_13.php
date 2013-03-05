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
require_once("../classes/sms.php");
require_once("../scheduler/notification_ctrl.php");
class Api
{
	public function __construct()     
	{
		$this->requestText  = array('TWILIOAPI_LOGIN','TWILIOAPI_REGISTRATION','TWILIOAPI_FORGOTPASSWORD','TWILIOAPI_USERLIST','TWILIOAPI_SENDVOICESMS',
									'TWILIOAPI_REPLYVOICESMS','TWILIOAPI_INBOX_OUTBOX_MSG','TWILIOAPI_UPDATEREADSTATUS','TWILIOAPI_CALLLOG',
									'TWILIOAPI_GETREPLYMSG','TWILIOAPI_RECEIVEDVSMS','TWILIOAPI_SENDOUTBOXVSMS','TWILIOAPI_AUTH_LOGIN',
									'TWILIOAPI_AUTH_RESEND','TWILIOAPI_TEXTMSG_INBOX','TWILIOAPI_USERCONTACTS_IMPORTS','TWILIOAPI_PLACE_A_CALL');
		$this->requestType  = @$_REQUEST['keyword'];
		$this->objEmail	    = new clsEmail();
		$this->objUtility   = new Utility();
		$this->notification = new pushNotification();
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
			case 'TWILIOAPI_REGISTRATION' :  // User registration.
          			return $this->registration();
          		break;		
			case 'TWILIOAPI_CALLLOG' :
					return $this->callLog();
          		break;		
			case 'TWILIOAPI_USERLIST' :
					return $this->userList();
          		break;			
			case 'TWILIOAPI_SENDVOICESMS' :
					return $this->sendVoiceSms();
          		break;	
			case 'TWILIOAPI_RECEIVEDVSMS':
			  		return $this->receivedVoiceSms();
          		break;	
			case 'TWILIOAPI_SENDOUTBOXVSMS':
			  		return $this->sendOutboxVoiceSms();
          		break;	
			case 'TWILIOAPI_REPLYVOICESMS' :
					return $this->replyVoiceSms();
          		break;			
			case 'TWILIOAPI_INBOX_OUTBOX_MSG':
			  		return $this->inboxOutboxVoiceSms();
          		break;		
			case 'TWILIOAPI_UPDATEREADSTATUS':						
					return $this->updateVSmsStatus();
          		break;	 	
			case 'TWILIOAPI_GETREPLYMSG':						
				return $this->getReplyVoiceSms();
			break; 			
			case 'TWILIOAPI_AUTH_LOGIN':						
				return $this->getLoginWithAuthCode();
			break;  
			case 'TWILIOAPI_AUTH_RESEND':						
				return $this->sendResendAuthKey();
			break; 
			case 'TWILIOAPI_TEXTMSG_INBOX':						
				return $this->getTextInbox();
			break; 
			case 'TWILIOAPI_USERCONTACTS_IMPORTS':						
				return $this->userContactImport();
			break;
			case 'TWILIOAPI_PLACE_A_CALL':
				return $this->placeACall();
			break;			
		   	case 'LIVERYCAB_TEST' :  // Testing  
          			return $this->test();
          		break;		
		}
	}
	
	
	/*
	* Resend user Authentication code for the both Android/Iphone
	*http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_AUTH_RESEND&mobile={MOBILE}
	*/
	private function sendResendAuthKey()
	{
		global $_objDB;	
		$mobile  = trim(Request::data('mobile'));		
		
		if($mobile!='')
		{
				$_objDB->ExecuteMultiQuery("SELECT activeCode FROM tblUsers WHERE mobileNo ='".$mobile."' limit 1");				
				if($data = $_objDB ->FetchRecords())
				{					
					$mobmsg = "Your authentication code : ".$data[0]['activeCode'].". Please enter your auth code to activate your account";
			  		 sms::sendSms("6465536390",$mobile,$mobmsg);
					$msg = array("Message"=>"success","userAuthKey"=>$data[0]['activeCode'],"Status"=>"ok");	
				}
				else
				{
					$msg = array("Message"=>"Invalid_mobile","Status"=>"Failed");	
				}	
		}
		else
		{
			$msg = array("Message"=>"session_expired","Status"=>"Failed");	
		}
		 if(!isset($msg))
		 	$msg = array("Message"=>"Invalid","Status"=>"Failed");
		return(json_encode($msg));
	}
			
   /*
	* User Auth  Login for the both Android/Iphone
	*http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_AUTH_LOGIN&uid={UID}&authcode=(AUTH)
	*/
	private function getLoginWithAuthCode()
	{
		global $_objDB;	
		$uid      = Request::data('uid');
		$authcode = Request::data('authcode');
		
		if($uid!='' && $authcode!='')
		{
				$result = $_objDB->ExecuteMultiQuery("SELECT activeCode,id,mobileNo FROM tblUsers WHERE id ='".$uid."' AND activeCode='".$authcode."' limit 1");				
				if($data = $_objDB ->FetchRecords())
				{
					$_objDB ->ExecuteMultiQuery("UPDATE tblUsers SET status='1' WHERE id='".$data[0]['id']."'");	
					$msg = array("Message"=>"success","UserId"=>$data[0]['id'],"UserName"=>$data['0']['mobileNo'],"Status"=>"ok");	
				}
				else
				{
					$msg = array("Message"=>"Invalid_authcode","Status"=>"Failed");	
				}	
		}
		else
		{
			$msg = array("Message"=>"session_expired","Status"=>"Failed");	
		}
		 if(!isset($msg))
		 	$msg = array("Message"=>"Invalid","Status"=>"Failed");
		return(json_encode($msg));
	}
	
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
		if($mobileNo!='' && $pass!='')
		{    
				$result = $_objDB->ExecuteMultiQuery("SELECT id,firstName, email, mobileNo,status, purPhoneNo FROM tblUsers WHERE mobileNo='".$mobileNo."' AND password='".base64_encode($pass)."'");
				$data = $_objDB ->FetchRecords();
				if(!empty($data))
				{
					if($data['0']['status']=='1')
					{
						$userId = $data['0']['id'];
						$msg = array("Message"=>"Active","UserId"=>$userId,"firstName"=>$data['0']['firstName'],"UserName"=>$data['0']['mobileNo'],"email"=>$data['0']['email'],"purPhoneNo"=>$data['0']['purPhoneNo'],"Status"=>"ok");				
						if($deviceId!='' && $deviceType!='' && $deviceToken!='')
						{
							$query = " INSERT INTO tblDeviceTokens (userId, deviceId,deviceToken,deviceType) values('$userId','$deviceId','$deviceToken','$deviceType')  ON DUPLICATE KEY UPDATE deviceId='".$deviceId."',deviceToken='".$deviceToken."',deviceType='".$deviceType."'";
							$_objDB->ExecuteMultiQuery($query); 
						}
						else 
						{ 
							$msg = array("Message"=>"missing_device_id_token_type","Status"=>"Failed"); 
						}
					}
					else
					{
						$msg = array("Message"=>"Deactive","UserId"=>$data['0']['id'],"firstName"=>$data['0']['firstName'],"email"=>$data['0']['email'],"purPhoneNo"=>$data['0']['purPhoneNo'],"loginStatus"=>$data['0']['status'],"Status"=>"Failed");
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
	/*
	*Validation functions
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
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_REGISTRATION&mobileNo={mobileNo}&email={EMAIL}&password={PASSWORD}&firstName={firstName}&lastName={lastName}&address={address}&AreaCode={AreaCode}&deviceId=(DEVICEID)&deviceToken={DEVICETOKEN}&deviceType={iphone}				
	*/
  private function registration()
	{
		global $_objDB;
		global $_objDB,$site_url,$temp_site_url, $MAIL_TEAM_REGARDS;
		global $AdminEmail, $imageUrl, $imageTitle;
		global $GAccountSid, $GAuthToken;
				
		$mobileNo      		= trim(Request::data('mobileNo'));
		$firstName			= trim(Request::data('firstName'));
		$lastName			= trim(Request::data('lastName'));
		$email	       		= trim(Request::data('email'));
		$address      		= trim(Request::data('address'));
		$password			= base64_encode(Request::data('password'));
		$AreaCode			= trim(Request::data('AreaCode'));
		$deviceId   		= Request::data('deviceId');
		$deviceType 		= strtolower(Request::data('deviceType'));
		$deviceToken		= trim($_REQUEST['deviceToken']);
		$deviceToken        = str_replace(array('<','>'),'',$deviceToken);
		$PhoneNumber 		= "";		
			
				
		if($mobileNo!='' && $AreaCode!='' && is_numeric($AreaCode))
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
				$activeCode = Utility::getRandomAlphaNumeric(4);
				
				date_default_timezone_set("America/Los_Angeles");	
				$_objDB ->ExecuteMultiQuery("INSERT INTO tblUsers SET mobileNo='".$mobileNo."' , firstName='".$firstName."' , lastName='".$lastName."' , email='".$email."', password='".$password."', address='".$address."',activeCode='".$activeCode."', dateAdded=now()");	
				$LastId = $_objDB->InsertedID();
				
				/* CODE TO CREATE TWLIO SUBACCOUNT STARTS HERE */
				if(!empty($LastId))
				  {
					$ParentAccountSid = $GAccountSid;
    				$ParentAuthToken = $GAuthToken;
    
    				$client = new Services_Twilio($ParentAccountSid, $ParentAuthToken);
    
					// Mock data for our newly created local application user
					$local_user = array("id"=>$LastId, "first_name"=>"$firstName", "last_name"=>"$lastName", "email"=>"$email", "twilio_account_sid"=>"", "twilio_auth_token"=>"");

					/*$client = new Services_Twilio('AC123', '123');*/
					$subaccount = $client->accounts->create(array(
					"FriendlyName" => $local_user['email']
					));
	
					//update and save our local_user
					$local_user['twilio_account_sid'] = $subaccount->sid;
					$local_user['twilio_auth_token'] = $subaccount->auth_token;
					
					if(!empty($local_user['twilio_account_sid']))
					  {	 
					  
					  	$_objDB ->ExecuteMultiQuery("UPDATE tblUsers SET subaccountSid='".$local_user['twilio_account_sid']."', subaccountAuthToken='".$local_user['twilio_auth_token']."' WHERE id='".$LastId."'");
						 
						  /* Code to purchase a number for subaccount starts here */
						 
							// create a new Twilio client for subaccount
							$subaccount_client = new Services_Twilio($local_user['twilio_account_sid'], $local_user['twilio_auth_token']);
							$number = $subaccount_client->account->incoming_phone_numbers->create(array(
							"AreaCode" => "$AreaCode",
							));
				
					  		$PhoneNumber = $number->phone_number;
							
							if(empty($PhoneNumber))
							 {
							 	$_objDB ->ExecuteMultiQuery("DELETE FROM tblUsers WHERE id='".$LastId."'");
						 	 	$msg = array("Message"=>"SubAccountCannotBeCreated","Status"=>"Failed");
						 	 	return(json_encode($msg));
							 }
							else
							 {
								$_objDB ->ExecuteMultiQuery("UPDATE tblUsers SET purPhoneNo='".$PhoneNumber."' WHERE id='".$LastId."'");
							 }
							
							
						 /* Code to purchase a number for subaccount ends here */
					  }
				    else
					   {
						 $_objDB ->ExecuteMultiQuery("DELETE FROM tblUsers WHERE id='".$LastId."'");
						 $msg = array("Message"=>"SubAccountCannotBeCreated","Status"=>"Failed");
						 return(json_encode($msg));
					   }
				  }
				
				 /* CODE TO CREATE TWLIO SUBACCOUNT ENDS HERE */

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
		    	/*$message = ereg_replace('\~\~NAME\~\~', stripsl($firstName." ".$lastName), $message);*/	
				$message = ereg_replace('\~\~NAME\~\~', $email, $message);		
				$message = ereg_replace('\~\~USERNAME\~\~', $mobileNo, $message);		
				$message = ereg_replace('\~\~PASSWORD\~\~', base64_decode($password), $message);
				$message = ereg_replace('\~\~PurchasedNumber\~\~', $PhoneNumber, $message);
				$message = ereg_replace('\~\~imageTitle\~\~', $imageTitle, $message);	
				$message = ereg_replace('\~\~MAIL_TEAM_REGARDS\~\~',$MAIL_TEAM_REGARDS, $message);
				$mail_to = $email;
			/*$mail_to = "sunil.malik@classicinformatics.com";*/
			   $this->objEmail->CommonMail("",$mail_to,$mailSubject,$message);
			   /**For message*/
			   $mobmsg = "Thank for register with twilio app. your authentication code : ".$activeCode.". Please enter your auth code to activate your account";
			   sms::sendSms("+16465536390",'+'.$mobileNo,$mobmsg);
			   $msg = array("Message"=>"Registered_Successfully","Status"=>"Ok","UserId"=>$LastId,"mobileNo"=>$mobileNo,"email"=>$email, "subaccountSid"=>$local_user['twilio_account_sid'], "PhoneNumber"=>$PhoneNumber);
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
	
	/*
	* User USER LIST for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_USERLIST				
	*/
  private function userList()
	{
		global $_objDB;	
		$query = "SELECT SQL_CALC_FOUND_ROWS id, mobileNo, firstName, lastName, email FROM tblUsers ORDER BY firstName Asc";	
		$usrList = $_objDB ->ExecuteMultiQuery($query);
		$usrList  = $_objDB ->FetchRecords();
	   if(!empty($usrList))
		{
			foreach($usrList as $row)
			{
				$msg[] = array("Message"=>"Success","ID"=>$row['id'], "mobileNo"=>$row["mobileNo"], "firstName"=>$row["firstName"], "lastName"=>$row["lastName"], "email"=>$row["email"], "Status"=>"Ok"); 
			}
		}
	   else
		{
			$msg[] = array("Message"=>"Record_not_found","Status"=>"Failed"); 
		}
		return(json_encode($msg));
	}
		
  /*
	* User USER LIST for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_SENDVOICESMS&fromId={fromId}&toId={toId}&vSmsFile={vSmsFile}&msgtitle={MSGTITLE}				
	*/
  private function sendVoiceSms()
  {
	global $_objDB;	
	global $site_url,$temp_site_url, $user_sms;
	$msgtitle		= trim(Request::data('msgtitle'));
	$fromId			= trim(Request::data('fromId'));
	$toId			= trim(Request::data('toId'));
	$vSmsFile		= trim(Request::data('vSmsFile'));
	@$FromUserName   = $this->UserName($fromId);
	
	if($fromId != "" && $toId!="" && is_numeric($fromId) && is_numeric($toId))
	{  
		if(isset($_FILES["vSmsFile"]["name"]) && $_FILES["vSmsFile"]["error"]=="0")
		{
			$tmp1 = str_replace(" ","",$_FILES["vSmsFile"]["name"]);
			$tmp1 = str_replace("(","",$tmp1);
			$tmp1 = str_replace(")","",$tmp1);
			$tmp1 = str_replace("{","",$tmp1);
			$tmp1 = str_replace("}","",$tmp1);
			$tmp1 = str_replace("[","",$tmp1);
			$tmp1 = str_replace("]","",$tmp1);
			$tmp1 = str_replace("'","",$tmp1);
			$tmp1 = str_replace("&","",$tmp1);
			$tmp1 = str_replace(";","",$tmp1);
			$tmp1 = str_replace(":","",$tmp1);
			$tmp1 = str_replace("%","",$tmp1); 
			$tmpFile = date("mdyhis")."_".$tmp1;
			$tmpPict 		= $user_sms.$tmpFile;
			if(upload_my_file($_FILES["vSmsFile"]["tmp_name"],"..".$tmpPict))
			{
				$mp3_name_arr 	= getFileExtenssion($tmpFile);
				$mp3_name 		= $mp3_name_arr['file_name'];
				$mp3_destination = AUDIO_PATH.$mp3_name.".mp3";
				$mp3_desturl	 = AUDIO_URL.$mp3_name.".mp3";
				generateMp3("..".$tmpPict,$mp3_destination);	
				@unlink("..".$tmpPict);	
				$query  = "INSERT INTO tblVoiceSms(fromId, toId,voiceMsgTitle, vSmsFile, dateAdded) VALUES('".$fromId."', '".$toId."','".$msgtitle."','".$mp3_desturl."' , now())";
				$_objDB ->ExecuteMultiQuery($query);
				$LastId = $_objDB->InsertedID();
				/**send text msg*/
				$mobile = $this->getMobileNo($toId);
				$mobmsg = $FromUserName.' just sent you a new voice message.';
				//sms::sendSms("6465536390",$mobile,$mobmsg);
				/**Push notification for receiver*/		//sam here only for push notification....	
				$deviceDetais = $this->userDeviceDetails($toId);
				if(@$deviceDetais[0]["deviceToken"]!="" && @$deviceDetais[0]["deviceType"]!="")
				{
					$this->notification->sendPushNotiForVoicemsg($deviceDetais[0]["deviceToken"],$deviceDetais[0]["deviceType"],@$FromUserName);
				}
				$msg = array("Message"=>"sent_Successfully","vSmsId"=>$LastId,"Status"=>"Ok");	
			}
			else
			{							
				$msg = array("Message"=>"error_sending_voice_sms","Status"=>"Failed");	
			}
		}
		else
		{						
			$msg = array("Message"=>"error_sending_voice_sms","Status"=>"Failed");	
		}			
	}		
	else
	{
		$msg = array("Message"=>"session_expired","Status"=>"Failed"); 			  
	}
	return(json_encode($msg));
  }
  
				
	/*
	* User voice reply message
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_REPLYVOICESMS&msgid={MSGID}&fromId={fromId}&toId={toId}&vSmsFile={vSmsFile}&msgtitle={MSGTITLE}			
	*/
  private function replyVoiceSms()
  {
	global $_objDB;	
	global $site_url,$temp_site_url, $user_sms;
	$msgtitle	   = trim(Request::data('msgtitle'));
	$fromId		   = trim(Request::data('fromId'));
	$msgid		   = trim(Request::data('msgid'));
	$toId		   = trim(Request::data('toId'));
	$vSmsFile	   = trim(Request::data('vSmsFile'));
	@$FromUserName = $this->UserName($fromId);
	$mobile        = $this->getMobileNo($toId);
	
	if($fromId != "" && $toId!="" && $msgid!="" && is_numeric($fromId) && is_numeric($toId) && is_numeric($msgid))
	{  
		if(isset($_FILES["vSmsFile"]["name"]) && $_FILES["vSmsFile"]["error"]=="0")
		{
			$tmp1 = str_replace(" ","",$_FILES["vSmsFile"]["name"]);
			$tmp1 = str_replace("(","",$tmp1);
			$tmp1 = str_replace(")","",$tmp1);
			$tmp1 = str_replace("{","",$tmp1);
			$tmp1 = str_replace("}","",$tmp1);
			$tmp1 = str_replace("[","",$tmp1);
			$tmp1 = str_replace("]","",$tmp1);
			$tmp1 = str_replace("'","",$tmp1);
			$tmp1 = str_replace("&","",$tmp1);
			$tmp1 = str_replace(";","",$tmp1);
			$tmp1 = str_replace(":","",$tmp1);
			$tmp1 = str_replace("%","",$tmp1); 
			$tmpFile = date("mdyhis")."_".$tmp1;
			$tmpPict 		= $user_sms.$tmpFile;
			if(upload_my_file($_FILES["vSmsFile"]["tmp_name"],"..".$tmpPict))
			{
				$mp3_name_arr 	= getFileExtenssion($tmpFile);
				$mp3_name 		= $mp3_name_arr['file_name'];
				$mp3_destination = AUDIO_PATH.$mp3_name.".mp3";
				$mp3_desturl	 = AUDIO_URL.$mp3_name.".mp3";
				generateMp3("..".$tmpPict,$mp3_destination);	
				@unlink("..".$tmpPict);	
				$query  = "INSERT INTO tblVoiceSms(fromId, toId,voiceMsgTitle, vSmsFile,replyStatus,dateAdded) VALUES('".$fromId."', '".$toId."','".$msgtitle."', '".$mp3_desturl."' ,'Y', now())";
				$_objDB ->ExecuteMultiQuery($query);
				$LastId = $_objDB->InsertedID();
				/**send text msg*/				
				$mobmsg = $FromUserName.' just sent you a new voice message.';
				//sms::sendSms("6465536390",$mobile,$mobmsg);
				/**Reply message status*/
				if($LastId!="")
				{
					$_objDB ->ExecuteMultiQuery("UPDATE tblVoiceSms SET replyStatus='Y',replyMsgId='".$LastId."' WHERE id='".$msgid."'");					
				}				
				/**Push notification for receiver*/		
				$deviceDetais = $this->userDeviceDetails($toId);
				if(@$deviceDetais[0]["deviceToken"]!="" && @$deviceDetais[0]["deviceType"]!="")
				{
					$this->notification->sendPushNotiForVoicemsg($deviceDetais[0]["deviceToken"],$deviceDetais[0]["deviceType"],@$FromUserName);
				}
				$msg = array("Message"=>"sent_Successfully","vSmsId"=>$LastId,"Status"=>"Ok");	
			}
			else
			{							
				$msg = array("Message"=>"error_sending_voice_sms","Status"=>"Failed");	
			}
		}
		else
		{						
			$msg = array("Message"=>"error_sending_voice_sms","Status"=>"Failed");	
		}			
	}		
	else
	{
		$msg = array("Message"=>"session_expired","Status"=>"Failed"); 			  
	}
	return(json_encode($msg));
  }
  
	/*
	* User reply voice message
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_GETREPLYMSG&replymsgid={REPLYID}				
	*/
  private function getReplyVoiceSms()
  {
	global $_objDB;	
	global $site_url,$temp_site_url;
	$replymsgid  = trim(Request::data('replymsgid'));
	if(!empty($replymsgid) && is_numeric($replymsgid))
	{
		/*User inbox message here...*/
		$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id,VoiceSms.fromId,VoiceSms.voiceMsgTitle, VoiceSms.vSmsFile,VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,
		          Users.lastName FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.fromId = Users.id 
				  WHERE VoiceSms.replyMsgId='".$replymsgid."'";
		$_objDB ->ExecuteMultiQuery($query);
		$usrReply  = $_objDB ->FetchRecords();
		if(!empty($usrReply))
		{
			$vSmsFile	   = $usrReply[0]["vSmsFile"];
			if($vSmsFile!="" && file_exists("..".$vSmsFile))
			{
				$vSmsFile = $temp_site_url.$vSmsFile;
			}
			else{ $vSmsFile = ""; }
			$msg[] = array("Message"=>"Success","messageTitle"=>$usrReply[0]['voiceMsgTitle'],"ID"=>$usrReply[0]['id'],"firstName"=>$usrReply[0]["firstName"], "lastName"=>$usrReply[0]["lastName"], "vSmsFile"=>$vSmsFile,"Status"=>"Ok"); 		
		}
		else
		{
			$msg[] = array("Message"=>"record_not_found","Status"=>"Failed"); 	
		}		
	}
	else
	{
		$msg[] = array("Message"=>"session_expired","Status"=>"Failed"); 	
	}
	return(json_encode($msg));
  }
    /*
	* User USER LIST for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_INBOX_OUTBOX_MSG&userId={userId}				
	*/
  private function inboxOutboxVoiceSms()
  {
	global $_objDB;	
	global $site_url,$temp_site_url;
	$userId  = trim(Request::data('userId'));
	if(!empty($userId) && is_numeric($userId))
	{
		/*User inbox message here...*/
		$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id,VoiceSms.replyStatus,VoiceSms.replyMsgId, VoiceSms.fromId, VoiceSms.vSmsFile, VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,  Users.lastName FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.fromId = Users.id WHERE VoiceSms.toId='".$userId."' ORDER BY VoiceSms.id desc";
		$_objDB ->ExecuteMultiQuery($query);
		$usrInboxList  = $_objDB ->FetchRecords();
		if(!empty($usrInboxList))
		{
			foreach($usrInboxList as $row)
			{
				$vSmsFile	   = $row["vSmsFile"];
				if($vSmsFile!="" && file_exists("..".$vSmsFile))
				{
					$vSmsFile = $temp_site_url.$vSmsFile;
				}
				else{ $vSmsFile = ""; }
				$time = $this->timeDiff(strtotime($row["dateAdded"]));
				$msgInbox[] = array("ID"=>$row['id'],"replyStatus"=>$row['replyStatus'],"replyMsgId"=>$row['replyMsgId'], "firstName"=>$row["firstName"], "lastName"=>$row["lastName"], "vSmsFile"=>$vSmsFile, "readStatus"=>$row["readStatus"], "dateAdded"=>$time); 
			}
		}
		else
		{
			$msgInbox= ""; 
		}
		/*User outbox message here...*/
		$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id, VoiceSms.fromId, VoiceSms.vSmsFile, VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,  Users.lastName FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.toId = Users.id WHERE VoiceSms.fromId='".$userId."' ORDER BY VoiceSms.id desc";
		$_objDB ->ExecuteMultiQuery($query);
		$usrOutboxList  = $_objDB ->FetchRecords();
		if(!empty($usrOutboxList))
		{
			foreach($usrOutboxList as $row)
			{
				$vSmsFile	   = $row["vSmsFile"];
				if($vSmsFile!="" && file_exists("..".$vSmsFile))
				{
					$vSmsFile = $temp_site_url.$vSmsFile;
				}
				else{ $vSmsFile = ""; }
				$time = $this->timeDiff(strtotime($row["dateAdded"]));
				$msgOutbox[] = array("ID"=>$row['id'], "firstName"=>$row["firstName"], "lastName"=>$row["lastName"], "vSmsFile"=>$vSmsFile, "readStatus"=>$row["readStatus"], "dateAdded"=>$time); 
			}
		}
		else
		{
			$msgOutbox= ""; 
		}
		$msg[] = array("Message"=>"Success","messageInbox"=>$msgInbox, "messageOutbox"=>$msgOutbox,"Status"=>"Ok"); 
	}
	else
	{
		$msg[] = array("Message"=>"session_expired","Status"=>"Failed"); 	
	}
	return(json_encode($msg));
  }
  
      /*
	* User USER LIST for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_RECEIVEDVSMS&userId={userId}				
	*/
  private function receivedVoiceSms()
	{
		global $_objDB;	
		global $site_url,$temp_site_url;
	
		$userId  = trim(Request::data('userId'));
		
	   if(!empty($userId) && is_numeric($userId))
	    {
		 	$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id,VoiceSms.replyStatus,VoiceSms.voiceMsgTitle,VoiceSms.replyMsgId, VoiceSms.fromId, VoiceSms.vSmsFile, VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,Users.id uid,  Users.lastName FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.fromId = Users.id WHERE VoiceSms.toId='".$userId."' ORDER BY VoiceSms.id desc";
		
		$usrList = $_objDB ->ExecuteMultiQuery($query);
		$usrList  = $_objDB ->FetchRecords();
		   if(!empty($usrList))
			{
				foreach($usrList as $row)
				{
					 $vSmsFile	   = $row["vSmsFile"];
					 
					 if($vSmsFile!="" && file_exists("..".$vSmsFile))
							{
								$vSmsFile = $temp_site_url.$vSmsFile;
							}
					 else
					       $vSmsFile = "";
							/*$vSmsFile = $temp_site_url."/images/no-logo-big2x.png";*/
					$time = $this->timeDiff(strtotime($row["dateAdded"]));
					$msg[] = array("Message"=>"Success","messageTitle"=>$row['voiceMsgTitle'],"messageId"=>$row['id'],"userId"=>$row['uid'],"replystatus"=>$row['replyStatus'],"replymsgid"=>$row['replyMsgId'], "firstName"=>$row["firstName"], "lastName"=>$row["lastName"], "vSmsFile"=>$vSmsFile, "readStatus"=>$row["readStatus"], "dateAdded"=>$time, "Status"=>"Ok"); 
						
				}
			}
		   else
		    {
					$msg[] = array("Message"=>"Record_not_found","Status"=>"Failed"); 
			}
		}
	  else
	    {
					$msg[] = array("Message"=>"session_expired","Status"=>"Failed"); 	
			
		}
			
			return(json_encode($msg));
	}
	
	
   /*
	* User USER LIST for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_SENDOUTBOXVSMS&userId={userId}				
	*/
  private function sendOutboxVoiceSms()
	{
		global $_objDB;	
		global $site_url,$temp_site_url;
	
		$userId  = trim(Request::data('userId'));
		
	   if(!empty($userId) && is_numeric($userId))
	    {
		 	$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id,VoiceSms.voiceMsgTitle, VoiceSms.fromId, VoiceSms.vSmsFile, VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,  Users.lastName FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.toId = Users.id WHERE VoiceSms.fromId='".$userId."' ORDER BY VoiceSms.id desc";
		
		$usrList = $_objDB ->ExecuteMultiQuery($query);
		$usrList  = $_objDB ->FetchRecords();
		   if(!empty($usrList))
			{
				foreach($usrList as $row)
				{
					 $vSmsFile	   = $row["vSmsFile"];
					 
					 if($vSmsFile!="" && file_exists("..".$vSmsFile))
							{
								$vSmsFile = $temp_site_url.$vSmsFile;
							}
					 else
					       $vSmsFile = "";
							/*$vSmsFile = $temp_site_url."/images/no-logo-big2x.png";*/
					$time = $this->timeDiff(strtotime($row["dateAdded"]));
					$msg[] = array("Message"=>"Success","messageTitle"=>$row['voiceMsgTitle'],"ID"=>$row['id'], "firstName"=>$row["firstName"], "lastName"=>$row["lastName"], "vSmsFile"=>$vSmsFile, "readStatus"=>$row["readStatus"], "dateAdded"=>$time, "Status"=>"Ok"); 
						
				}
			}
		   else
		    {
					$msg[] = array("Message"=>"Record_not_found","Status"=>"Failed"); 
			}
		}
	  else
	    {
					$msg[] = array("Message"=>"session_expired","Status"=>"Failed"); 	
			
		}
			
			return(json_encode($msg));
	}	
  
    /*
	* get User text message
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_TEXTMSG_INBOX&userId={userId}				
	*/
  private function getTextInbox()
	{
	   global $_objDB;		
	   $userId  = trim(Request::data('userId'));
		if(!empty($userId) && is_numeric($userId))
		{
			/*$query = "SELECT * FROM tblTextSms WHERE toId='".$userId."' ORDER BY txtMsgId desc";
			*/
			$query = "SELECT TextSms.*, Users.firstName, Users.lastName FROM tblTextSms As TextSms LEFT JOIN tblUsers AS Users ON TextSms.fromId = Users.id  WHERE toId='".$userId."' ORDER BY txtMsgId desc";
			$_objDB ->ExecuteMultiQuery($query);
			$usrList  = $_objDB ->FetchRecords();
			if(!empty($usrList))
			{
				foreach($usrList as $row)
				{
					$time = $this->timeDiff(strtotime($row["dateAdded"]));
					$firstName = $row["firstName"];
					$lastName  = $row["lastName"];
					  
					$msg[] = array("Message"=>"Success","firstName"=>$firstName,"lastName"=>$lastName,"txtMsgId"=>$row['txtMsgId'],"txtMessage"=>$row['Message'], "dateAdded"=>$time, "Status"=>"Ok"); 
				}
			}
			else
			{
			$msg[] = array("Message"=>"Record_not_found","Status"=>"Failed"); 
			}
		}
		else
		{
			$msg[] = array("Message"=>"session_expired","Status"=>"Failed"); 			
		}		
		return(json_encode($msg));
	}
  
	/*
	* User UPdate Read Status for the both Android/Iphone
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_UPDATEREADSTATUS&smsId={smsId}				
	*/
  private function updateVSmsStatus()
  {
	global $_objDB;	
	$smsId  = trim(Request::data('smsId'));
	if(!empty($smsId) && is_numeric($smsId))
	{
		$query = "UPDATE tblVoiceSms SET readStatus='0' WHERE id='".$smsId."'";
		$_objDB ->ExecuteMultiQuery($query);
		$msg = array("Message"=>"Success","smsId"=>$smsId,"Status"=>"Ok");
	}
	else
	{
		$msg = array("Message"=>"session_expired","Status"=>"Failed"); 			 			
    }
	return(json_encode($msg));
 }
 
 
	/*
	* User contact import
	* http://twilio.demos.classicinformatics.com/api/?keyword=TWILIOAPI_USERCONTACTS_IMPORTS&uid={UID}&name={NAME}&mob={MOB}				
	*/
  private function userContactImport()
  {
	global $_objDB;	
	$uid  = trim(Request::data('uid'));
	$name = trim(Request::data('name'));
	$mob  = trim(Request::data('mob'));	
	if($mob!="" && $name!="")
	{
		$mobile = explode(",",$mob);
		$nameE = explode(",",$name);
	}
	
	if($uid!='' && (isset($mobile) && is_array($mobile)) && is_array($nameE))
	{			
		$query = "SELECT uid FROM tblUserContact WHERE uid = '".$uid."'";		
		$_objDB ->ExecuteMultiQuery($query);	
		$data  = $_objDB ->FetchRecords();
		if(!empty($data))
		{
			$_objDB ->ExecuteMultiQuery("DELETE FROM tblUserContact WHERE uid='".$uid."'");
		}
		for($i=0; $i<=count($mobile); $i++)
		{
			if(@$nameE[$i]!="" && @$mobile[$i]!="")
			{
				$modName = @str_replace(array('(',')','-','"','<br />','\\\"','\\'),'',strip_tags($nameE[$i]));
				$modmobile = @str_replace(array('(',')',' ','-','"','<br />','\\\"','\\'),'',strip_tags($mobile[$i]));
				$query2  = "INSERT INTO tblUserContact(uid, name, mobile) VALUES('".$uid."','".trim($modName)."', '".trim($modmobile)."')";			
				$_objDB ->ExecuteMultiQuery($query2);
			}						
		}
		$msg = array("Message"=>"Success","Status"=>"Ok");
	}
	else
	{
		$msg = array("Message"=>"session_expired","Status"=>"Failed"); 			 			
    }
	return(json_encode($msg));
 }
 
 	/*
	* User contact import
	* http://www.levmobile.com/api/?keyword=TWILIOAPI_PLACE_A_CALL&from={from}&to={to}	
	*/
  private function placeACall()
  {
	global $_objDB;	
	// Place a call from the subaccount
	/* Outgoing Caller ID you have previously validated with Twilio */
	$from  = trim(Request::data('from'));
	
	/* Outgoing Number you wish to call */
	$to    = trim(Request::data('to'));
	if($from!="" && $to!="")
	{
	 
	/* Callback Url responsing with TwiML when the call is answered */
	$url = 'http://twimlets.com/message?Message%5B0%5D=Hello%20from%20your%20subaccount';
 
 	$client = new Services_Twilio("ACca6a4a015d3d08619113c620bb487a1f", "eaa3ea00262516ed04de2b86629d145a");
 
	/* make Twilio REST request to initiate outgoing call */
	$call = $client->account->calls->create($from, $to, $url);
	$msg = array("Message"=>"connected","call"=>$call,"Status"=>"Success"); 
	}
	else
	{
		$msg = array("Message"=>"session_expired","Status"=>"Failed"); 			 			
    }
	return(json_encode($msg));
 }
	
	protected function DuplicateUser($mobileNo)
	{
		global $_objDB;
		$Duplicateusr = $_objDB ->ExecuteMultiQuery("SELECT mobileNo FROM tblUsers WHERE mobileNo='".$mobileNo."'");
		$Duplicateusr = $_objDB ->FetchRecords();
		$array          = @$Duplicateusr['0']['mobileNo'];
		return $array;
	}
	
	/**User Name*/
	protected function UserName($uid)
	{
		global $_objDB;
		$UserName = $_objDB ->ExecuteMultiQuery("SELECT CONCAT(firstName,' ',lastName) username FROM tblUsers WHERE id='".$uid."'");
		$UserName = $_objDB ->FetchRecords();
		return $UserName['0']['username'];
	}
	
	/**Mobile number*/
	protected function getMobileNo($uid)
	{
		global $_objDB;
		$UserName = $_objDB ->ExecuteMultiQuery("SELECT mobileNo FROM tblUsers WHERE id='".$uid."'");
		$UserName = $_objDB ->FetchRecords();
		return $UserName['0']['mobileNo'];
	}
	
	protected function userDeviceDetails($userId)
	{
		global $_objDB;
		$userDeviceDetails = $_objDB ->ExecuteMultiQuery("SELECT deviceToken,deviceType FROM tblDeviceTokens WHERE userId='".$userId."' LIMIT 1");
		$userDeviceDetails = $_objDB ->FetchRecords();		
		return $userDeviceDetails;
	}		
	
	protected function DuplicateEmail($email)
	{
		global $_objDB;
		$Duplicateusr = $_objDB ->ExecuteMultiQuery("SELECT email FROM tblUsers WHERE email='".$email."'");
		$Duplicateusr = $_objDB ->FetchRecords();
		$array          = @$Duplicateusr['0']['email'];
		return $array;
	}	
	/**Time difference */
	protected function timeDiff($time, $opt = array())
	{	
		global $_objDB;
		$defOptions = array(
							'to' => 0,
							'parts' => 2,
							'precision' => 'second',
							'distance' => TRUE,
							'separator' => ', ',
							'next'=>'ago',
							'prev'=>'away'
		);
		$opt = array_merge($defOptions, $opt);
		$query = "SELECT NOW() as t";
		$_objDB ->ExecuteMultiQuery($query);
		$span = $_objDB ->FetchRecords();
		
		(!$opt['to']) && ($opt['to'] = strtotime($span[0]["t"]));
		$str = '';
		$diff = ($opt['to'] > $time) ? $opt['to']-$time : $time-$opt['to'];
		$periods = array(
						'year' => 31556926,
						'month' => 2629744,
						'week' => 604800,
						'day' => 86400,
						'hour' => 3600,
						'minute' => 60,
						'second' => 1
		);
		if ($opt['precision'] != 'second')
		$diff = round(($diff/$periods[$opt['precision']])) * $periods[$opt['precision']];
		(0 == $diff) && ($str = 'less than 1 '.$opt['precision']);
		foreach ($periods as $label => $value) 
		{
			(($x=floor($diff/$value))&&$opt['parts']--) && $str.=($str?$opt['separator']:'').($x.' '.$label.(($x>1)?'s':''));
			if ($opt['parts'] == 0 || $label == $opt['precision']) break;
			$diff -= $x*$value;
		}
		$opt['distance'] && $str.=" ".(($str&&$opt['to']>$time)?$opt['next']:$opt['prev']);
		return $str;
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