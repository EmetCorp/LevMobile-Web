<?php
include_once("ctrl/authkey_ctrl.php");
if(@$_SESSION['userId']!='')
{
	header('Location:dashboard.php');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio Client</title>
<link href="css/main.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
function removeerrors(errorr)
{
	document.getElementById(errorr).style.display="none";
}

function funvalidation()
 {
	var aa=1;
	var errortofocus="";
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var phoneRegEx =/^[0-9]+$/i;
	var num_patt=/^[0-9]$/i;	
	var chkFocus = false;		
	
	if(document.getElementById("mobNumber").value=="" )
	{
		document.getElementById('mobNumbererr').innerHTML = "*Please enter mobile number.";
		if(chkFocus==false)
		errortofocus='mobNumber';
		aa=0;
		chkFocus = true;				
	}	
	
	
 	if(aa==0)
	{
		document.getElementById(errortofocus).focus();
		return false;
	}
	else
	{
		return true;
	}
}
</script>
</head>
<body>
 <p style="margin-top:220px; color:red;">&nbsp;    </p>
 <p style="color:red;"> <? if(@$_SESSION["authMsgresend"]!="") { echo $_SESSION["authMsgresend"]; $_SESSION["authMsgresend"]=""; } else { echo ""; } ?></p>
<form method="post">
 <input name="submitted" type="hidden" value="yes">
  <input type="text" id="mobNumber" name="mobNumber" placeholder="Enter mobile number" onkeydown="removeerrors('mobNumbererr');"/>
  <span id="mobNumbererr" style="color:red; font-size:14px;" ></span>
 <input type="submit" value="Send" style="cursor:pointer"  onclick="return funvalidation();" />
  <a href="login.php" style="text-decoration:none;"> <input type="button" style="cursor:pointer" value="Cancel"  /> </a>
</form>

</body>
</html>