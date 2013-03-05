<?php

class frontenduser  
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		
	}
	
	/*
	 Description : Function to check , Admin is login or not
   */
	public function CheckTwilioUser($userMobile)
	{
		global $_objDB;
		$_objDB ->ExecuteMultiQuery("SELECT mobileNo,id FROM tblUsers WHERE mobileNo='".$userMobile."' LIMIT 1");
		$CheckUserRegister = $_objDB ->FetchRecords();
		return $CheckUserRegister;
	}	
	// Add Text SMS
	public function addTextMsg($fromId,$msg)
	{
		global $_objDB;
		$_objDB ->ExecuteMultiQuery("INSERT INTO tblTextSms SET toId='".$fromId."',Message='".addslashes($msg)."'");		
	}  
	
}//end of class
?>