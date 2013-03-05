<?php
ob_start();
session_start();
include("includes/config/db.php");
include("includes/functions/functions.php");
include("sess_check.php");

if(isset($_POST['update']) &&($_POST['update']!=''))
{
	$up = new db;

	$up->query("update tbladmin set admin_name = '".$_POST['adminuser']."' , admin_pass = '".$_POST['adminpass']."' , admin_email = '".$_POST['adminemail']."'  where admin_id = '".$_SESSION['AdminId']."'");
	$_SESSION['sess_msg'] = 'Admin information updated successfully.';
}
$ad = new db;
$ad->query("select * from tbladmin where admin_id = '".$_SESSION['AdminId']."'"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="en" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/reset.css" /> <!-- RESET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/main.css" /> <!-- MAIN STYLE SHEET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->
	<link rel="alternate stylesheet" media="screen,projection" type="text/css" href="css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->
	<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/style.css" /> <!-- GRAPHIC THEME -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/mystyle.css" /> <!-- WRITE YOUR CSS CODE HERE -->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/jquery.switcher.js"></script>
	<script type="text/javascript" src="js/toggle.js"></script>
	<script type="text/javascript" src="js/ui.core.js"></script>
	<script type="text/javascript" src="js/ui.tabs.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[1,0]];
		$("table").tablesorter({
			headers: {
				0: { sorter: false },
				7: { sorter: false }
			}
		});
	});
	</script>
	<title><?=ADMINTITLE?></title>
</head>

<body>

<div id="main">

	<!-- header -->
	<? include("header.php");?>
    <!-- header -->
	<hr class="noscreen" />

	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<? include("left.php");?>
         <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>Change Admin Information &raquo;</h1>

			<form action="" method="post" name="frm" id="frm">
			<table width="100%" border="0" cellpadding="10" cellspacing="5" bgcolor="#E9E7E8">
              <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                <th colspan="3" align="left" valign="top">&nbsp;</th>
              </tr>
              <? if(@$_SESSION['sess_msg']!=''){?>
              <tr >
               <td colspan="3" align="left" valign="top" class="msg info" style="padding-left:40px;"><?=$_SESSION['sess_msg']?><? $_SESSION['sess_msg']='';?>							               </td>
              </tr>
              <? }?>
              <tr class="bgg1" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg1'">
                <td width="24%" align="left" valign="top" class="bgg3">Admin Username :</td>
                <td width="76%" colspan="2" align="left" valign="top" class="bgg3"><input name="adminuser" type="text" class="input-text" id="textfield4" style="width:250px;" value="<?=$ad->rs['admin_name']?>" size="30" maxlength="50" /></td>
              </tr>
              <tr class="bgg3" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg3'">
                <td align="left" valign="top">Admin Password :</td>
                <td colspan="2" align="left" valign="top"><input name="adminpass" type="text" class="input-text"  id="textfield5" style="width:250px;" value="<?=$ad->rs['admin_pass']?>" maxlength="50"/></td>
              </tr>
              <tr class="bgg3" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg3'">
                <td align="left" valign="top">Admin Email Address :</td>
                <td colspan="2" align="left" valign="top"><input name="adminemail" type="text" class="input-text"  id="textfield6" style="width:250px;" maxlength="80" value="<?=$ad->rs['admin_email']?>"/></td>
              </tr>
              
              <tr class="bgg3" onmouseover="this.className='bgg2'" onmouseout="this.className='bgg3'">
                <td align="left" valign="top">&nbsp;</td>
                <td colspan="2" align="left" valign="top"><label>
                  <input type="submit" name="update" class="input-submit" id="button2" value="Update Information" />
                </label></td>
              </tr>
            </table>
          </form>
            
			

			
<div class="fix"></div>

			<h3>&nbsp;</h3>

	  </div> 
		
	</div> 

	<hr class="noscreen" />

	
	<? include("footer.php");?>
    <!-- /footer -->

</div> <!-- /main -->

</body>
</html>
