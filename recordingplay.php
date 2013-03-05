<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/Utility.php");
include('common.php');
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}
if(@$_GET['msgid']!="")
{
	$query = "SELECT SQL_CALC_FOUND_ROWS VoiceSms.id, VoiceSms.fromId,VoiceSms.voiceMsgTitle, VoiceSms.vSmsFile, VoiceSms.readStatus, VoiceSms.dateAdded, Users.firstName,  Users.lastName FROM tblVoiceSms AS VoiceSms INNER JOIN tblUsers AS Users ON VoiceSms.toId = Users.id 
	          WHERE VoiceSms.id='".base64_decode($_GET['msgid'])."' ORDER BY VoiceSms.id desc";
    $_objDB ->ExecuteMultiQuery($query);
    $usrOutboxList  = $_objDB ->FetchRecords();
}

 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio Client</title>
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link href="skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="js/jplayer.playlist.min.js"></script>
<script type="text/javascript" src="js/audio.js"></script>
</head>
<body>
<p style="margin:240px 0px 16px; "> <a href="inbox.php">Inbox</a> |  <a href="logout.php">Logout</a></p>
<br/>
<form name="inv_frm" action="" method="post">
  <table width="95%" align="center" border="1px solid #000;">
    <tr>
      <td height="35"  style="background:#000; color:#FFF;">Time Line</td>
      <td height="35" style="background:#000; color:#FFF;">Message Play</td>
    </tr>
    <?php 
  if(!empty($usrOutboxList))
  {
  $k=0;
foreach ($usrOutboxList as $msg) { ?>
    <tr>
      <td>Reply of message title <?=$msg['voiceMsgTitle']?><br/>
	  <?=common::userNameSimple($_SESSION['userId']);?>
        have reply a voice message to
        <?=$msg['firstName'].' '.$msg['lastName']?>
        at <br/>
        <?=common::timeDiff(strtotime($msg['dateAdded']))?>
        !</td>
      <td><embed type="application/x-shockwave-flash" src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?=$msg['vSmsFile']?>" width="350" height="27" quality="best"></embed></td>
    </tr>
    <?php } } else {?>
    
      <td style="color:red;" colspan="4">Record not found</td>
      <? } ?>
  </table>
</form>
</body>
</html>