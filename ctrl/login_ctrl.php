<?
require_once('common/inc/global.inc.php');
class clsLogin 
{
  public $erromsg=array();
  private $UserName;
  private $Password;

	public function __construct()
	{
		/*Error message---------------------------------*/
	     if(Request::data('msg')=='expired')
		 {
			 $this->erromsg['expired'] = "Your login session has expired, please login again.";
		 }	
		 if(Request::data('msg')=='logout')
		 {
			 $this->erromsg['logout'] = "You have been logged out successfully.";
		 }
		  if(Request::data('msg')=='Invalid')
		 {
			 $this->erromsg['Invalid'] = "Please Enter Valid User Name/Password.";
		 }
		   if(Request::data('msg')=='forgot')
		 {
			 $this->erromsg['forgot'] = "Please check the mail to receive your password.";
		 }
		 
		 /*------------------------------------------------------------*/	
	
	}

	public function PageLoad()
	{
		global $_objDB;
		$this->UserName = Request::data('txtUserName');
		$this->Password = Request::data('pwdPassword');	
		
		/*------------------------------------------------------------*/
		// if the form is submitted
		if((Request::data('submitted') == 'yes')  && Request::data('txtUserName')!='' && Request::data('pwdPassword') !='')
		{				
			$sql = "SELECT id, mobileNo,status FROM tblUsers WHERE mobileNo='".$this->UserName."' and password='".base64_encode($this->Password)."' limit 1";
			$_objDB ->ExecuteMultiQuery($sql);
			if($data = $_objDB ->FetchRecords())
			{
				if($data[0]['status'] != "0")
				{
					$_SESSION['userId']=$data[0]['id'];				
					header ("Location:"._WWWROOT."/dashboard.php");
					exit;
				}
				else
				{								
					header ("Location:"._WWWROOT."/login.php?authuid=".base64_encode($data[0]['id']));
					exit;
				}
				
			}else{
				header ("Location:"._WWWROOT."/login.php?msg=Invalid");
				exit;
			}
		}
		// if the form is submitted
		if((Request::data('authsubmitted') != "") && Request::data('authid')!="")
		{				
			$authCode = trim(Request::data('authid'));
			$uid = Request::data('authsubmitted');
			$sql = "SELECT activeCode,id FROM tblUsers WHERE id ='".$uid."' AND activeCode='".$authCode."' limit 1";
			$_objDB ->ExecuteMultiQuery($sql);
			if($data = $_objDB ->FetchRecords())
			{
				$_SESSION['userId']=$data[0]['id'];	
				$_objDB ->ExecuteMultiQuery("UPDATE tblUsers SET status='1' WHERE id='".$_SESSION['userId']."'");				
				header ("Location:"._WWWROOT."/dashboard.php");	
			}
			else
			{
				$_SESSION["authMsg"] = "Invalid authentication code ..Please try again";
				header ("Location:"._WWWROOT."/login.php?authuid=".base64_encode($uid));
				exit;
			}
		}
	}	
}

$objPage = new clsLogin();
$objPage->PageLoad();
?>