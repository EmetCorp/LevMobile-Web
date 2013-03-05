<?php
require_once('common/inc/global.inc.php'); 
unset($_SESSION['userId']);
header('Location: '._WWWROOT."/login.php?msg=logout");
exit;
?>