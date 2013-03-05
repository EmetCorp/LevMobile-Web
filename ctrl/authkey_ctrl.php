<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/sms.php");
class clsLogin 
{
  public $erromsg=array();
  private $UserName;
  private $Password;

	public function __construct()
	{
		 $this->urlQueryString  = !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING']  : '' ;
	}

	public function PageLoad()
	{
		global $_objDB;
		$this->Mobnumber = Request::data('mobNumber');
		
		/*------------------------------------------------------------*/
		// if the form is submitted
		if((Request::data('submitted') == 'yes')  && Request::data('mobNumber')!='')
		{				
			$sql = "SELECT activeCode  FROM tblUsers WHERE mobileNo='".$this->Mobnumber."' limit 1";
			$_objDB ->ExecuteMultiQuery($sql);
			if($data = $_objDB ->FetchRecords())
			{
				$_SESSION['authMsgresend'] = "Your auth code sent sucessfully .Please check your sms";	
				$mobmsg = "Your authentication code : ".$data[0]['activeCode'].". Please enter your auth code to activate your account";
				sms::sendSms("6465536390",$this->Mobnumber,$mobmsg);	
				header ("Location:"._WWWROOT."/authkey.php");
				exit;
				
			}else{
				$_SESSION['authMsgresend'] = "Invalid mobile number";		
				header ("Location:"._WWWROOT."/authkey.php");
				exit;
			}
		}		
	}	
}

$objPage = new clsLogin();
$objPage->PageLoad();
?>