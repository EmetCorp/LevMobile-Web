<?php
include_once("ctrl/signup_ctrl.php");
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
	document.getElementById(errorr).innerHTML = "";
}

function funvalidation()
 {
	var aa=1;
	var errortofocus="";
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var phoneRegEx =/^[0-9]+$/i;
	var num_patt=/^[0-9]$/i;	
	var chkFocus = false;	
	if(document.getElementById("txtMobile").value=="" )
	{
		document.getElementById('txtMobileerr').innerHTML = "*Please enter mobile number.";
		if(chkFocus==false)
		errortofocus='txtMobile';
		aa=0;
		chkFocus = true;				
	}
	else if(document.getElementById("txtMobile").value.search(phoneRegEx) == -1)
	{
		document.getElementById('txtMobileerr').innerHTML = "*Please enter numberic only.";
		if(chkFocus==false)
		errortofocus='txtMobile';
		aa=0;
		chkFocus = true;
	}	
	if(document.getElementById("txtfname").value=="" )
	{
		document.getElementById('txtfnameerr').innerHTML = "*Please enter first name.";
		if(chkFocus==false)
		errortofocus='txtfname';
		aa=0;
		chkFocus = true;				
	}
	if(document.getElementById("txtLname").value=="" )
	{
		document.getElementById('txtLnameerr').innerHTML = "*Please enter second name.";
		if(chkFocus==false)
		errortofocus='txtLname';
		aa=0;
		chkFocus = true;				
	}
	//------------------  Email  -------------------//
	
	if(document.getElementById("email").value=="" )
	{
		document.getElementById('emailerr').innerHTML = "*Please check your mail address.";
		if(chkFocus==false)
		errortofocus='email';
		aa=0;	
		chkFocus = true;			
	}
	else if(document.getElementById("email").value.search(emailRegEx) == -1)
	{
		document.getElementById('emailerr').innerHTML = "*Please enter a valid email address.";
		if(chkFocus==false)
		errortofocus='email';
		aa=0;
		chkFocus = true;
	} 
	//------------------  Password  -------------------//
	if(document.getElementById("password").value=="" )
	{
		document.getElementById('passworderr').innerHTML = "*Please enter password.";
		if(chkFocus==false)
		errortofocus='password';
		aa=0;	
		chkFocus = true;			
	}
	//------------------  Confirm Password  -------------------//
	if(document.getElementById("confirmation").value=="" )
	{
		document.getElementById('confirmationerr').innerHTML = "*Please enter confirm password.";
		if(chkFocus==false)
		errortofocus='confirmation';
		aa=0;
		chkFocus = true;
	}
	if(document.getElementById("password").value != document.getElementById("confirmation").value)
	{
		document.getElementById('confirmationerr').innerHTML = "*Your password does not match.";
		if(chkFocus==false)
		errortofocus='confirmation';
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
<form action="" method="post">
  <input name="submitted" type="hidden" value="yes" >
  <input type="text" id="txtfname" name="txtfname" style="margin-top:240px;" value="<?=@Request::data('txtfname')?>"  placeholder="Enter first name" onkeydown="removeerrors('txtfnameerr');" />
  <span id="txtfnameerr" style="color:red; font-size:14px;" ></span>
  <input type="text" id="txtLname" name="txtLname" value="<?=@Request::data('txtLname')?>"  placeholder="Enter second name" onkeydown="removeerrors('txtLnameerr');" />
  <span id="txtLnameerr" style="color:red; font-size:14px;" ></span>
  <input type="text" id="txtMobile" name="txtMobile"  value="<?=@Request::data('txtMobile')?>"  placeholder="Enter mobile number" onkeydown="removeerrors('txtMobileerr');" />
  <span id="txtMobileerr" style="color:red; font-size:14px;" ></span>
  <?php if(@$_SESSION["RmsgMobile"]!="") {?>
  <span style="color:red; font-size:14px;" ><?php echo $_SESSION["RmsgMobile"]; $_SESSION["RmsgMobile"]=""; ?></span>
  <?php } ?>
  <input type="text" id="email" name="email" placeholder="Enter email" onkeydown="removeerrors('emailerr');" />
  <span id="emailerr" style="color:red; font-size:14px;" ></span>
  <?php if(@$_SESSION["RmsgEmail"]!="") {?>
  <span style="color:red; font-size:14px;" ><?php echo $_SESSION["RmsgEmail"]; $_SESSION["RmsgEmail"]=""; ?></span>
  <?php } ?>
  <input type="password" id="password" value="<?=@Request::data('password')?>" name="password" placeholder="Enter user password" onkeydown="removeerrors('passworderr');" />
  <span id="passworderr" style="color:red; font-size:14px;" ></span>
  <input type="password" id="confirmation" value="<?=@Request::data('password')?>" name="confirmation" placeholder="Re enter password" onkeydown="removeerrors('confirmationerr');" />
  <span id="confirmationerr" style="color:red; font-size:14px;" ></span>
  <input type="submit" value="Register" style="cursor:pointer;" onclick="return funvalidation();"  />
  <a href="login.php" style="text-decoration:none;">
  <input type="button" style="cursor:pointer" value="Cancel"  />
  </a>
</form>
</body>
</html>