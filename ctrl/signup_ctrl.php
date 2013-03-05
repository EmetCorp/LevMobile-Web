<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/Email.php");
require_once(_CLS_PATH . "/Utility.php");
require_once(_CLS_PATH . "/sms.php");

class userAdd
{
	public function __construct()
	{
	    $this->urlQueryString  	= !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING']  : '' ;
		$this->objEmail		    = new clsEmail();		
	}
			
	public function PageLoad()
	{
	     global $_objDB;
		 global $_objDB,$site_url,$temp_site_url, $MAIL_TEAM_REGARDS;
		 global $AdminEmail, $imageUrl, $imageTitle;
		 if(Request::data('submitted')=='yes' && Request::data('txtMobile')!="" && Request::data('email')!="")
		 {
			$mobileNo      		= trim(Request::data('txtMobile'));
			$email	       		= trim(Request::data('email'));
			$fname	       		= trim(Request::data('txtfname'));
			$lname	       		= trim(Request::data('txtLname'));
			$password			= base64_encode(Request::data('password'));
			
			$checkUser = $this->DuplicateUser($mobileNo);
			$checkEmail = $this->DuplicateEmail($email);
			if(!empty($checkUser))
			{
				$_SESSION["RmsgEmail"] = "Mobile number already in use";
			}
		    elseif(!empty($checkEmail))
			{
				$_SESSION["RmsgMobile"] = "Email already in use";
			}
			else
			{
				$activeCode = Utility::getRandomAlphaNumeric(4);
				$_objDB ->ExecuteMultiQuery("INSERT INTO tblUsers SET mobileNo='".$mobileNo."',firstName='".$fname."',lastName='".$lname."' , email='".$email."', password='".$password."',activeCode='".$activeCode."', dateAdded=now()");	
				$LastId = $_objDB->InsertedID();
				$_SESSION['userId'] = $LastId;	
				
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
				$message = ereg_replace('\~\~NAME\~\~', $email, $message);		
				$message = ereg_replace('\~\~USERNAME\~\~', $mobileNo, $message);		
				$message = ereg_replace('\~\~PASSWORD\~\~', base64_decode($password), $message);
				$message = ereg_replace('\~\~imageTitle\~\~', $imageTitle, $message);	
				$message = ereg_replace('\~\~MAIL_TEAM_REGARDS\~\~',$MAIL_TEAM_REGARDS, $message);
				$mail_to = $email;
				$this->objEmail->CommonMail("",$mail_to,$mailSubject,$message);
				/**Sms send functionality*/
				$mobmsg = "Thank for register with twilio app. your authentication code : ".$activeCode.". Please enter your auth code to activate your account";
				sms::sendSms("6465536390",$mobileNo,$mobmsg);
				header ("Location:"._WWWROOT."/dashboard.php");
			}			 
		 }
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
	
}//end of class

$objPage = new userAdd();
$objPage->PageLoad();

?>