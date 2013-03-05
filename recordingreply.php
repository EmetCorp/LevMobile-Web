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
	if((@$_SESSION['session_recording_reply']!="") && $_GET["toid"]!="")
	{
		$toId = $_GET["toid"];
		$msgid = $_GET["msgid"];
		$msgtitle = $_GET["msgtitle"];
		//from user Details
		$userFromDetails = common::userDetails($_SESSION["userId"]);
		$fromUserName = $userFromDetails[0]["username"]; 
		//to user details
		$userToDetails = common::userDetails($toId);		
		$mobileNo = $userToDetails[0]["mobileNo"]; 
		//Text Message
		$mobmsg = $fromUserName.' just sent you a new voice message.';
		
		$sql    = "INSERT INTO tblVoiceSms SET fromId='".$_SESSION["userId"]."',toId='".$toId."',voiceMsgTitle='".$msgtitle."',
				   vSmsFile='"."/site_files/user_sms/".$_SESSION["session_recording_reply"]."',replyStatus='Y',dateAdded=NOW()";
		$_objDB ->ExecuteMultiQuery($sql);
		$LastId = $_objDB->InsertedID();
		/**Reply message status*/
		if($LastId!="")
		{
			$_objDB ->ExecuteMultiQuery("UPDATE tblVoiceSms SET replyStatus='Y',replyMsgId='".$LastId."' WHERE id='".$msgid."'");					
		}
		/**send text message*/
		sms::sendSms("6465536390",$mobileNo,$mobmsg);
		/**device token*/
		$_objDB ->ExecuteMultiQuery("SELECT deviceToken,deviceType FROM tblDeviceTokens WHERE userId='".$toId."'");
		$userDevilDetails = $_objDB ->FetchRecords();
		if($userDevilDetails[0]["deviceToken"]!="" && $userDevilDetails[0]["deviceType"]!="")
		{
			$pushnoti = new pushNotification();
			$pushnoti->sendPushNotiForVoicemsg($userDevilDetails[0]["deviceToken"],$userDevilDetails[0]["deviceType"],$fromUserName);
		}
		$_SESSION['voice_sms_sent'] = "Voice message sent sucessfully";
		$_SESSION["session_recording_reply"] = "";
		header("location:recordingplay.php?msgid=".base64_encode($LastId));
	}
	else
	{
		$_SESSION['voice_sms_reply_sent'] = "Please first record voice message and then send";
	}
}

$sql = "SELECT mobileNo,id,firstName,lastName FROM tblUsers";
$_objDB ->ExecuteMultiQuery($sql);
$res = $_objDB ->FetchRecords();
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
</head>
<body>
<p style="color:red; font-size:14px; margin-top:240px; z-index:999;">
  <? if(@$_SESSION["voice_sms_reply_sent"]!="") { echo $_SESSION["voice_sms_reply_sent"]; $_SESSION["voice_sms_reply_sent"]=""; } else { echo "&nbsp;"; } ?>
</p>
<div class="record-bleep fl" style="margin-right: 10px;display:block">
  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="454" height="165" id="BeepRecorder">
    <param name="movie" value="<?='flash/BeepRecorder.swf?saveurl=recordingreplysave.php';?>" />
    <param name="quality" value="high" />
    <param name="bgcolor" value="#ffffff" />
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="allowFullScreen" value="true" />
    <param name="wmode" value="transparent" />
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="<?='flash/BeepRecorder.swf?saveurl=recordingreplysave.php';?>" width="454" height="165">
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
  <input type="submit" value="Send" style="cursor:pointer; margin: 10px auto 0;"  />
   <a href="inbox.php" style="text-decoration:none; cursor:pointer;"><input type="button" value="Cancel" style="cursor:pointer; margin: 10px auto 0;"  /></a>
</form>
</body>
</html>