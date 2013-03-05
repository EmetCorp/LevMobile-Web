<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/Utility.php");
include('common.php');
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}
$query = "SELECT * FROM tblTextSms WHERE toId='".$_SESSION['userId']."' ORDER BY txtMsgId desc";
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
<p style="margin:240px 0px 16px; ">  <a href="dashboard.php">Back</a> | <a href="logout.php">Logout</a></p>
<br/>
<form name="inv_frm" action="" method="post">
  <table width="95%" align="center" border="1px solid #000;">
    <tr>
      <td width="46%" height="35"  style="background:#000; color:#FFF;">Time Line</td>
      <td width="54%" height="35" style="background:#000; color:#FFF;">Text Message</td>
    </tr>
    <?php 
  if(!empty($usrInboxList))
  {
  $k=0;
foreach ($usrInboxList as $msg) { ?>
    <tr>
      <td>
	     Admin has sent you a text message at        
        <br/>
        <?=common::timeDiff(strtotime($msg['dateAdded']))?>
        !</td>
      <td><?=$msg['Message']?></td>
      
    </tr>
    <?php } } else {?>    
      <td style="color:red;" colspan="4">Record not found</td>
      <? } ?>
  </table>
</form>
</body>
</html>