<?php
ob_start();
session_start();
include("includes/config/db.php");
include("includes/functions/functions.php");
$u = new db;
$u->query("update tbladmin set admin_login = '".time()."' ,admin_ip = '".$_SERVER['REMOTE_ADDR']."' where admin_id = '".@$_SESSION['AdminId']."'");
$_SESSION['AdminId']='';
$_SESSION['admin_name']='';
setcookie ("AdminUser", "", time() - 3600);
setcookie ("AdminPass", "", time() - 3600);
session_destroy();
session_regenerate_id();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Logout In admin Area</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" border="0">

  <tr>
    <td align="center" height="500"><table width="50%" border="0" class="brd2">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="70%" border="0" align="center" class="brd">
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" class="link1">You have been successfully   logged out. <a href="index.php">Click here</a> to login again.</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
      <label></label></td>
  </tr>
</table>

</body>
</html>
