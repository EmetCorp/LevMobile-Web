<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/Utility.php");
include('common.php');
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}
$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id,VoiceSms.voiceMsgTitle,VoiceSms.replyStatus,VoiceSms.replyMsgId, VoiceSms.fromId, VoiceSms.vSmsFile, VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,  Users.lastName,Users.id uid FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.fromId = Users.id WHERE 
		 VoiceSms.toId='".$_SESSION['userId']."' ORDER BY VoiceSms.id desc";
$_objDB ->ExecuteMultiQuery($query);
$usrInboxList  = $_objDB ->FetchRecords();
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio Client</title>
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link href="skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>

</head>
<body>
<p style="margin:240px 0px 16px; ">  <a href="outbox.php">Outbox</a> | <a href="dashboard.php">Back</a> | <a href="logout.php">Logout</a></p>
<br/>
<form name="inv_frm" action="" method="post">
  <table width="95%" align="center" border="1px solid #000;">
    <tr>
      <td height="35"  style="background:#000; color:#FFF;">Time Line</td>
      <td height="35" style="background:#000; color:#FFF;">Message Play</td>
      <td height="35" style="background:#000; color:#FFF;">Message Reply</td>
    </tr>
    <?php 
  if(!empty($usrInboxList))
  {
  $k=0;
foreach ($usrInboxList as $msg) { ?>
    <tr>
      <td>Message title : <?=$msg['voiceMsgTitle']?><br/>
	  <?=$msg['firstName'].' '.$msg['lastName']?>
        has sent you a voice message at        
        <br/>
        <?=common::timeDiff(strtotime($msg['dateAdded']))?>
        !</td>
      <td><embed type="application/x-shockwave-flash" src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?=$msg['vSmsFile']?>" width="350" height="27" quality="best"></embed></td>
      <td>
      <? if($msg['replyStatus'] == "N") {?>
      <a href="recordingreply.php?toid=<?=$msg['uid']?>&msgid=<?=$msg['id']?>&msgtitle=<?=$msg['voiceMsgTitle']?>">Reply</a>
       <? } elseif($msg['replyStatus'] == "Y" && $msg['replyMsgId']== 0) { ?><a href="javascript:;" onclick="alert('This is replied message of <?=$msg['voiceMsgTitle']?>')">Reply</a>
      <? } else { ?><a href="recordingplay.php?msgid=<?=base64_encode($msg['replyMsgId'])?>">Reply</a><? } ?></td>
    </tr>
    <?php } } else {?>
    
      <td style="color:red;" colspan="4">Record not found</td>
      <? } ?>
  </table>
</form>
</body>
</html>