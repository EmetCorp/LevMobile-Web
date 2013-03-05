<?php
session_start();
if($_SESSION['userId']=='')
{
	header('Location:login.php?msg=expired');
	exit();
}
// Include the Twilio PHP library
require 'api/Services/Twilio.php';
// Twilio REST API version
$version = '2010-04-01';
// Set our AccountSid and AuthToken
$sid = 'AC78689f5b95066c13cd60213da3901e99';
$token = 'e72f490c6e338466a46ec5c6ee2b295e';
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($sid, $token, $version);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio Client</title>
<link href="css/main.css"
type="text/css" rel="stylesheet" />
</head>
<body>
<p style="margin:240px 0px 16px; "> <a href="call.php">Back</a>  |
<a href="logout.php">Logout</a></p>
<table width="80%" align="center" border="1px solid #000;">
  <tr>
    <th height="35p" width="138" style="background:#000; color:#FFF;">Call From</th>
    <th height="35p" width="165" style="background:#000; color:#FFF;">Call To</th>
    <th height="35p" width="169" style="background:#000; color:#FFF;">Start Time</th>
    <th height="35p" width="140" style="background:#000; color:#FFF;">Duration</th>
  </tr>
  <?php 
foreach ($client->account->calls as $call) { ?>
  <tr>
    <td><?=$call->from?></td>
    <td><?=$call->to?></td>
    <td><?=$call->start_time?></td>
    <td><?=$call->duration?></td>
  </tr>
  <?php } ?>
</table>
</body>
</html>