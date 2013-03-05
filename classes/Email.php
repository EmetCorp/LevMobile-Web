<?php

/**
 * Main Email Template Class
 *
 * Class to manipulate the data and information for different email templates
 *
 */

;
require_once(_LIB_PATH.'/EMLmail.php');

class clsEmail
{


    /**
	* Constructor
	*/
	public function __construct()
	{
		$this->strSendername    = "Levmobile";
		
	}



     public function sendCourtstausMail($to_name='', $to_addr='', $adContent)
	 {
		  
		  $from_name =   "Levmobile ";
		  $from_addr =   _SUPPORT_EMAIL;
       // echo $to_name."". $to_addr."".$from_name."". $from_addr."".$to_subject."".$adContent;die;
		  clsMimeMail::SendNow($to_name, $to_addr, $from_name, $from_addr, "Court Activation mail by levmobile admin", $adContent);
     }


	 public function CommonMail($to_name='', $to_addr='',$to_subject,$adContent)
	 {   
	 		 $from_name =   "Levmobile ";
		      $from_addr =   _SUPPORT_EMAIL;
		         clsMimeMail::SendNow($to_name, $to_addr, $from_name, $from_addr, $to_subject, $adContent);
	 }
}//end of class



	 