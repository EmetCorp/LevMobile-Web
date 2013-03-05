<?php
ob_start();
session_start();
include("includes/config/db.php");
include("includes/functions/functions.php");
if(@$_SESSION['AdminId']!=''){header("location:dashboard.php");}
include("adminlogin.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=ADMINTITLE?></title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script src="user_script/user_script.js"></script>

<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	color: #FF0000;
}
-->
</style>
</head>
<body>
<table width="48%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <td align="left" style="padding-left:10px;">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" style="padding-left:10px;"><h1>ADMIN  : <?=TITLE?></h1></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <th height="30" align="left">: - Authentication Required : -</th>
          </tr>
          <tr>
            <td><form id="form1" name="admin_frm" method="post" action="">
              
               <div id="rem">
              <table width="100%" border="0" cellpadding="10" cellspacing="1" bgcolor="#E9E7E8">
               
         		<? if(@$_SESSION['msg_err2']!=''){?>
                <tr>
                  <td colspan="2" align="center" valign="top" class="formErrors"><?=$_SESSION['msg_err2']?><? $_SESSION['msg_err2']='';?></td>
                  </tr>
                <? }?>
                <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                  <td align="left" valign="top">&nbsp;</td>
                  <td align="left" valign="top"><div id="userError" style="width:210px;"></div></td>
                </tr>
                <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                  <td width="42%" align="left" valign="middle">Admin Username :</td>
                  <td width="58%" align="left" valign="top" class="green_bg"><label>
                    <input name="admin_user" type="text" class="inputbox" id="admin_user" style="width:200px;" onchange="AdminValidation"  value="<?=@$_COOKIE['AdminUser']?>" maxlength="50"/>
                  </label></td>
                </tr>
                <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                  <td align="left" valign="middle">Admin Password :</td>
                  <td align="left" valign="top"><input name="admin_pass" type="password" class="inputbox" id="admin_pass" style="width:200px;" onchange="AdminValidation" onkeyup="AdminValidation();" value="<?=@$_COOKIE['AdminPass']?>" maxlength="50"/></td>
                </tr>
                <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                  <td align="right" valign="middle"><input type="checkbox" name="Remain" id="checkbox" value="Y" <? if(@$_COOKIE['AdminPass']!=''){echo "CHECKED";}?>/></td>
                  <td align="left" valign="top">Remain logged in on this computer?</td>
                </tr>
                
                <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                  <td align="left" valign="middle">&nbsp;</td>
                  <td align="left" valign="top"><a href="javascript:void('0')" onclick="Forgot('Y');">Forgotten your password?</a></td>
                </tr>
                <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                  <td colspan="2" align="center" valign="top"><input type="submit" name="login" id="login" class="button1" value="Login Admin" title="Admin Login" onclick="return AdminValidation();"  style="cursor:pointer;"></td>
                </tr>
              </table>
              </div>
            </form></td>
          </tr>
          <tr>
            <td>
            <div id="forgot" style="display:none;">
            
            <form id="form1" name="form1" method="post" action="">
            <table width="100%" border="0" cellpadding="10" cellspacing="1" bgcolor="#E9E7E8">
            <? if(@$_SESSION['msg_err']!=''){?>
                <tr>
                  <td colspan="2" align="center" valign="top" class="formErrors"><?=$_SESSION['msg_err']?><? $_SESSION['msg_err']='';?></td>
                  </tr>
                <? }?>  

              
              <tr class="bgg3" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg3'">
                <td width="42%" align="left" valign="middle">Enter Email Address :</td>
                <td width="58%" align="left" valign="top" class="green_bg"><label>
                  <input name="admin_email" type="text" class="inputbox" id="admin_email" style="width:200px;" onchange="AdminValidation"  value="<?=@$_POST['admin_user']?>" maxlength="50"/>
                </label></td>
              </tr>
              
              <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                <td align="left" valign="middle">&nbsp;</td>
                <td align="left" valign="top"><a href="javascript:void('0')" onclick="Forgot('N');">Remeber Me</a><a href="javascript:void('0')" onclick="Forgot('Y');"></a></td>
              </tr>
              <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                <td colspan="2" align="center" valign="top"><input name="send" type="submit" class="button1" id="button2" value="Send" style="cursor:pointer;" /></td>
              </tr>
            </table>
            </form>
            </div>            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<?
if((@$_COOKIE['AdminUser']!='') && ($_COOKIE['AdminPass']!=''))
{
?>
<script>
document.admin_frm.submit();
</script>
<? }?>
<? if(@$_POST['send']=='Send'){?>
<script>Forgot('Y');</script>
<? }?>
	

</body>
</html>
