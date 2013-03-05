<?php
require_once('scheduler/notification_ctrl.php');
$pushnoti = new pushNotification();
$deviceToken = "fe7ba832 7cea35fc 8b753ee2 0e886a12 1ff9085a e0b327c2 30b76be4 fc41572f";
$deviceType = "iphone";
$pushnoti->sendPushNotiForVoicemsg($deviceToken,$deviceType);
?>
