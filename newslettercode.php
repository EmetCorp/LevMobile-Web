<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/Utility.php");
if(isset($_POST["submit"]) && $_POST["submit"]=="OK")
{
	$email = $_POST["email"];
	$query = "INSERT INTO tblNewsletter (email) values ('$email') ON  DUPLICATE KEY UPDATE email=VALUES(email)";
    $_objDB ->ExecuteMultiQuery($query);	
	$_SESSION['sess_msg'] = ' Thanks a lot for successfully registering for news feed with LevMobile Team.';
	header("location:index.php");
}
?>