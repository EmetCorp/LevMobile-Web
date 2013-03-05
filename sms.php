<?php
require_once('common/inc/global.inc.php');
require_once('classes/frontenduser.php');
require_once('classes/sms.php');
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}

if((@$_POST['submitted'] == 'yes')  && (@$_POST['txtMobile']!='') && (@$_POST['txtMsg'] !=''))
{	
	$fromNo = "6465536390";
 
    $fromId	= $_SESSION['userId'];
	   
	$toNo   = trim($_POST["txtMobile"]);
	$msg 	= $_POST["txtMsg"];
	$objfrontenduser = new frontenduser();
	$userMobileCheck = $objfrontenduser->CheckTwilioUser($toNo);
	if(@$userMobileCheck[0]["mobileNo"] == $toNo)
	{
		$objfrontenduser->addTextMsg($userMobileCheck[0]["id"],$msg, $fromId);
	}
	else
	{	
	
	 sms::sendSms($fromNo,$toNo,$msg);				
	}
		
	$_SESSION['sms_sent'] = "Sms sent sucessfully";
}
$sql = "SELECT * FROM tblUserContact WHERE uid ='".$_SESSION["userId"]."'";
$_objDB ->ExecuteMultiQuery($sql);
$res = $_objDB ->FetchRecords();	  
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
	document.getElementById(errorr).innerHTML="";
}

function funvalidation()
 {
	var aa=1;
	var errortofocus="";
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var phoneRegEx =/^[0-9]+$/i;
	var num_patt=/^[0-9]$/i;	
	var chkFocus = false;	
	var str = "";
	if(document.getElementById("txtMobile").value=="")
	{
		document.getElementById('txtMobileerr').innerHTML = "*Please enter mobile number.";
		if(chkFocus==false)
		errortofocus='txtMobile';
		aa=0;
		chkFocus = true;				
	}	
	if(document.getElementById("txtMobile").value!="")
	{
		str		= document.getElementById("txtMobile").value;
		fval	=  str.substr(0,1);
		if(fval!='+')
		  {
			document.getElementById('txtMobileerr').innerHTML = "*Please enter valid mobile number.";
			if(chkFocus==false)
				errortofocus='txtMobile';
				aa=0;
				chkFocus = true;				  
		  }

	}
	if(document.getElementById("txtMsg").value=="")
	{
		document.getElementById('txtMsgerr').innerHTML = "*Please enter message.";
		if(chkFocus==false)
		errortofocus='txtMsg';
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
<script type="text/javascript" src="http://www.javascripttoolbox.com/libsource.php/popup/combined/popup.js"></script>
<script language="javascript">
function openPopup(div)
{
	Popup.show(div);
	document.getElementById('fadeIn').style.display='block';
}
function fillMobileNo(mob)
{
	document.getElementById("txtMobile").value = mob;
	document.getElementById('txtMobileerr').innerHTML="";
	Popup.hide('modal');
	document.getElementById('fadeIn').style.display='none';
}
</script>
</head>
<body>
<p style="color:red; font-size:14px; margin-top:240px; z-index:999;">
  <? if(@$_SESSION["sms_sent"]!="") { echo $_SESSION["sms_sent"]; $_SESSION["sms_sent"]=""; } else { echo "&nbsp;"; } ?>
</p>
<form action="" method="post">
  <input name="submitted" type="hidden" value="yes" >
  <input type="text" id="txtMobile" name="txtMobile" placeholder="Enter mobile number" onkeydown="removeerrors('txtMobileerr');" />
  <a href="#" onclick="Popup.showModal('modal',null,null,{'screenColor':'#000','screenOpacity':.6});return false;" style="cursor:pointer;">Contact Import</a><br/>
  <span id="txtMobileerr" style="color:red; font-size:14px;" ></span><br/>
  <textarea id="txtMsg" name="txtMsg" cols="58" rows="6"  style="padding-left:10px; font-size:26px; width:480px; border: 1px solid #999999; box-shadow: 1px 2px 10px #BBBBBB; font-weight:bold;" placeholder="Enter message" onkeydown="removeerrors('txtMsgerr');"></textarea>
  <br/>
  <span id="txtMsgerr" style="color:red; font-size:14px;" ></span><br/>
  <input type="submit" value="Send SMS" style="cursor:pointer; margin: 10px auto 0;" onclick="return funvalidation();"  />
  <a href="dashboard.php" style="text-decoration:none;">
  <input type="button" value="Cancel" style="cursor:pointer;" />
  </a>
  <div id="modal" style="display:none; z-index:9999;  border:3px solid black;background-color:#CCCCCC; ">
    <?php if(!empty($res)) { ?>
    <div style="overflow:auto; height:200px; font-size:150%; text-align:left;">
      <ul>
        <?php 
 $k = 1; foreach($res as $us) { ?>
        <li style="list-style:none; cursor:pointer;" value="<?=$us["mobile"]?>" onclick="fillMobileNo('<?=$us["mobile"]?>');">
          <?=$us["mobile"]?>
          (
          <?=$us["name"]?>
          )</li>
        <?php } ?>
      </ul>
    </div>
    <? } else { ?>
    <div style="color:#F00">Contact not found</div>
    <? } ?>
    <input type="button" value="Cancel" onClick="Popup.hide('modal')" style="cursor:pointer;">
  </div>
</form>
</body>
</html>