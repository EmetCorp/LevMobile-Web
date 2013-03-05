<?php
include_once("ctrl/login_ctrl.php");
//style="text-decoration:none; cursor:pointer; border: 1px solid #999999; width:100px; font-size:24px; padding:10px; box-shadow: 1px 2px 10px #BBBBBB; background:#ece9d8;"
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
	if(document.getElementById("txtUserName").value=="" )
	{
		document.getElementById('txtUserNameerr').innerHTML = "*Please enter mobile number.";
		if(chkFocus==false)
		errortofocus='txtUserName';
		aa=0;
		chkFocus = true;				
	}
	
	if(document.getElementById("pwdPassword").value=="" )
	{
		document.getElementById('pwdPassworderr').innerHTML = "*Please enter mobile number.";
		if(chkFocus==false)
		errortofocus='pwdPassword';
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
function funvalidation2()
 {
	var aa=1;
	var errortofocus="";
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var phoneRegEx =/^[0-9]+$/i;
	var num_patt=/^[0-9]$/i;	
	var chkFocus = false;	
	if(document.getElementById("authid").value=="" )
	{
		document.getElementById('authiderr').innerHTML = "*Please enter auth code.";
		if(chkFocus==false)
		errortofocus='authid';
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
 <p style="color:red;"> <? if(@$_SESSION["authMsg"]!="") { echo $_SESSION["authMsg"]; $_SESSION["authMsg"]=""; } else { echo ""; } ?></p>
     <?php  if(isset($objPage->erromsg['logout']) || isset($objPage->erromsg['expired']) || isset($objPage->erromsg['Invalid']) || isset($objPage->erromsg['forgot']))
       { ?>	  
       <p style=" color:red;">   
        <?php if(isset($objPage->erromsg['logout']))  echo "&nbsp;".@$objPage->erromsg['logout']."&nbsp;"; ?>
        <?php if(isset($objPage->erromsg['expired']))  echo "&nbsp;".@$objPage->erromsg['expired']."&nbsp;"; ?>
        <?php if(isset($objPage->erromsg['Invalid']))  echo "&nbsp;".@$objPage->erromsg['Invalid']."&nbsp;"; ?>
        <?php if(isset($objPage->erromsg['forgot']))  echo "&nbsp;".@$objPage->erromsg['forgot']."&nbsp;"; ?>
        </p>
     <?php } ?>
     <p>If your account is not active.Please click on <a href="authkey.php">Resend</a> to activate your account</p>
<?php if(@$_GET["authuid"]==""){ ?>
<form method="post">
 <input name="submitted" type="hidden" value="yes">
  <input type="text" id="txtUserName" name="txtUserName" placeholder="Enter mobile number" onkeydown="removeerrors('txtUserNameerr');"/>
  <span id="txtUserNameerr" style="color:red; font-size:14px;" ></span>
  <input type="password" id="pwdPassword" name="pwdPassword" placeholder="Enter password" onkeydown="removeerrors('pwdPassworderr');" />
   <span id="pwdPassworderr" style="color:red; font-size:14px; margin-left:0px;" ></span><br/>
 <input type="submit" value="Login" style="cursor:pointer"  onclick="return funvalidation();" />
  <a href="signup.php" style="text-decoration:none;"> <input type="button" style="cursor:pointer" value="Register"  /> </a>
</form>
<?php } else { ?>
<form method="post">
 <input name="authsubmitted" type="hidden" value="<?=@base64_decode($_GET["authuid"])?>">
  <input type="text" id="authid" name="authid" placeholder="Enter your authentication code" onkeydown="removeerrors('authiderr');"/>
  <span id="authiderr" style="color:red; font-size:14px;" ></span> 
 <input type="submit" value="Login" style="cursor:pointer"  onclick="return funvalidation2();" />
  <a href="login.php" style="text-decoration:none;"> <input type="button" style="cursor:pointer" value="Cancel"  /> </a>
</form>
<?php } ?>
</body>
</html>