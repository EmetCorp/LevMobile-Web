<?php
session_start();
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio Client</title>
<link href="css/main.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://www.javascripttoolbox.com/libsource.php/popup/combined/popup.js"></script>
<script language="javascript">
function openPopup(div)
{
	Popup.show(div);
	document.getElementById('fadeIn').style.display='block';
}
</script>
</head>
<body>
<p style="margin:240px 0px 16px; "><a href="logout.php">Logout</a></p>
<p style="color:red; font-size:14px; z-index:999;">
  <? if(@$_SESSION["Rmsg"]!="") { echo $_SESSION["Rmsg"]; $_SESSION["Rmsg"]=""; } else { echo "";} ?>
</p>
<a href="call.php" style="text-decoration:none;">
<input type="button" value="Call" style="cursor:pointer;" />
</a> <a href="#" onclick="Popup.showModal('modal2',null,null,{'screenColor':'#000','screenOpacity':.6});return false;" style="text-decoration:none; cursor:pointer;">
<input type="button" value="SMS" style="cursor:pointer;" />
</a>
<div id="modal" style="border:3px solid black; background-color:#CCCCCC; padding:25px; font-size:150%; text-align:center; display:none; z-index:9999">
 <a href="recording.php" style="text-decoration:none; cursor:pointer;"> <input type="button" value="New" style="cursor:pointer;"></a>
  <a href="inbox.php" style="text-decoration:none; cursor:pointer;"><input type="button" value="Voice Message" style="cursor:pointer;" ></a>
 <input type="button" value="Cancel" onClick="Popup.hide('modal')" style="cursor:pointer;">
</div>
<a href="#" onclick="Popup.showModal('modal',null,null,{'screenColor':'#000','screenOpacity':.6});return false;" style="text-decoration:none; cursor:pointer;">
<input type="button" style="cursor:pointer;"  value="Voice" />
</a>


<div id="modal2" style="border:3px solid black; background-color:#CCCCCC; padding:25px; font-size:150%; text-align:center; display:none; z-index:9999">
 <a href="sms.php" style="text-decoration:none; cursor:pointer;"> <input type="button" value="New" style="cursor:pointer;"></a>
  <a href="textinbox.php" style="text-decoration:none; cursor:pointer;"><input type="button" value="Text Message" style="cursor:pointer;" ></a>
 <input type="button" value="Cancel" onClick="Popup.hide('modal2')" style="cursor:pointer;">
</div>
</body>
</html>