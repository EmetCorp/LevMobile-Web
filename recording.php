<?php
require_once('common/inc/global.inc.php');
require_once('scheduler/notification_ctrl.php');
require_once('classes/sms.php');
require_once('common.php');
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}
if((@$_POST['submitted'] == 'yes'))
{
	if((@$_POST['txtUser']!='') && ($_SESSION['session_bleep']!=""))
	{
		//from user Details
		$userFromDetails = common::userDetails($_SESSION["userId"]);
		$fromUserName = $userFromDetails[0]["username"]; 
		//to user details
		$userToDetails = common::userDetails($_POST["txtUser"]);		
		$mobileNo = $userToDetails[0]["mobileNo"]; 
		//Text Message
		$tinyUrl = "http://".$_SERVER['HTTP_HOST']."/messageplay.php?url=".$_SESSION["session_bleep"];
		$tinyUrlPost = tiny_url($tinyUrl);
		$mobmsg = $fromUserName.' just sent you a new voice message. Click here to listen '.$tinyUrlPost;
				
		$sql    = "INSERT INTO tblVoiceSms SET fromId='".$_SESSION["userId"]."',toId='".$_POST["txtUser"]."',
				   voiceMsgTitle='".$_POST['msgTitle']."',vSmsFile='"."/site_files/user_sms/".$_SESSION["session_bleep"]."',dateAdded=NOW()";
		$_objDB ->ExecuteMultiQuery($sql);
		/**send text message*/
		//28 June 2012
		//sms::sendSms("6465536390",$mobileNo,$mobmsg);
		/**device token*/
		$_objDB ->ExecuteMultiQuery("SELECT deviceToken,deviceType FROM tblDeviceTokens WHERE userId='".$_POST["txtUser"]."'");
		$userDevilDetails = $_objDB ->FetchRecords();
		if(@$userDevilDetails[0]["deviceToken"]!="" && @$userDevilDetails[0]["deviceType"]!="")
		{
			$pushnoti = new pushNotification();
			$pushnoti->sendPushNotiForVoicemsg($userDevilDetails[0]["deviceToken"],$userDevilDetails[0]["deviceType"],$fromUserName);
		}
		$_SESSION['voice_sms_sent'] = "Voice message sent sucessfully";
		$_SESSION["session_bleep"] = "";
	}
	else
	{
		$_SESSION['voice_sms_sent'] = "Please first record voice message and then send";
	}
}

$sql = "SELECT mobileNo,id,firstName,lastName FROM tblUsers";
$_objDB ->ExecuteMultiQuery($sql);
$res = $_objDB ->FetchRecords();

/**tinny url function here..*/
function tiny_url($url)
{
	return file_get_contents('http://tinyurl.com/api-create.php?url=' . $url);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio Client</title>
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link href="jplayer/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="jplayer/js/jquery.jplayer.min.js"></script>
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
	if(document.getElementById("txtUser").selectedIndex==0 )
	{
		document.getElementById('txtUsererr').innerHTML = "*Please select at least one user.";
		if(chkFocus==false)
		errortofocus='txtUser';
		aa=0;
		chkFocus = true;				
	}	
	
	if(document.getElementById("msgTitle").value=="")
	{
		document.getElementById('msgTitleerr').innerHTML = "*Please enter message title.";
		if(chkFocus==false)
		errortofocus='msgTitle';
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
<p style="color:red; font-size:14px; margin-top:240px; z-index:999;">
  <? if(@$_SESSION["voice_sms_sent"]!="") { echo $_SESSION["voice_sms_sent"]; $_SESSION["voice_sms_sent"]=""; } else { echo "&nbsp;"; } ?>
</p>
<div class="record-bleep fl" style="margin-right: 10px;display:block">
  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="454" height="165" id="BeepRecorder">
    <param name="movie" value="<?='flash/BeepRecorder.swf?saveurl=recordingsave.php';?>" />
    <param name="quality" value="high" />
    <param name="bgcolor" value="#ffffff" />
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="allowFullScreen" value="true" />
    <param name="wmode" value="transparent" />
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="<?='flash/BeepRecorder.swf?saveurl=recordingsave.php';?>" width="454" height="165">
      <param name="quality" value="high" />
      <param name="bgcolor" value="#ffffff" />
      <param name="allowScriptAccess" value="sameDomain" />
      <param name="allowFullScreen" value="true" />
      <param name="wmode" value="transparent" />
      <!--[if gte IE 6]>-->
      <p>Either scripts and active content are not permitted to run or Adobe Flash Player version	10.2.0 or greater is not installed. </p>
      <!--<![endif]--> 
      <a href="http://www.adobe.com/go/getflashplayer"> <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" /> </a> 
      <!--[if !IE]>-->
      
    </object>
    <!--<![endif]-->
  </object>
  <div id="savingProg"></div>
</div>
<form action="" method="post">
  <input name="submitted" type="hidden" value="yes"  >
  <input type="text" id="msgTitle" name="msgTitle" value="<?=@Request::data('msgTitle')?>"  placeholder="Enter message title" onkeydown="removeerrors('msgTitleerr');" />
  <span id="msgTitleerr" style="color:red; font-size:14px;" ></span><br/>
  <select style="height: 40px; padding: 10px; border: 1px solid #999999; width: 486px;" id="txtUser" name="txtUser" onChange="removeerrors('txtUsererr');">
  <option>Select</option>
  <?php foreach($res as $user){ ?>
   <option value="<?=$user['id']?>"><?=$user['mobileNo']?><?=$user['firstName']!="" ? "-(".$user['firstName']."&nbsp;".$user['lastName'].")" : ""?></option>
   <? } ?>
  </select><br/>
  <span id="txtUsererr" style="color:red; font-size:14px;" ></span>
  <input type="submit" value="Send" style="cursor:pointer; margin: 10px auto 0;" onclick="return funvalidation();"  />
   <a href="dashboard.php" style="text-decoration:none; cursor:pointer;"><input type="button" value="Cancel" style="cursor:pointer; margin: 10px auto 0;"  /></a>
</form>
</body>
</html>