<?php

class Admin  
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
	public function checkUserSession()
	{
		if(isset($_SESSION['userId'] ))
			return $_SESSION['userId'];
		else
		{
		    return false;
        }
	}

	
	/*
	 Description : To Verify Admin Login
   */
	public static function VerifyUserLogin()
	{
		if(!self::checkUserSession())
		{
			
			 header('Location: '._WWWROOT."/login.php/?msg=expired");
			 exit();
		}
	    return true;
	}
	
	
}//end of class
?>