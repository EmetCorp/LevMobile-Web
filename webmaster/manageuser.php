<?php
ob_start();
session_start();
include("../includes/config/db.php");
include("../includes/functions/functions.php");
include("sess_check.php");
$in = new db;

if((@$_POST['Submit']!='') && (@$_POST['ids']!=''))
{
  $arr=$_POST['ids'];	
	if(count($arr)>0)
  	{
	 
	    $res_id=implode(',',$arr); 
		if($_POST['action']=='Active'){$sql="UPDATE tbluser set status = 'Y' where uId in($res_id)";}
		if($_POST['action']=='Inactive'){$sql="UPDATE tbluser set status = 'N' where uId in($res_id)";}
		if($_POST['action']=='Delete'){ $sql="DELETE from  tbluser where uId in($res_id)"; }
		$res=mysql_query($sql);
		$_SESSION['sess_msg'] = 'Selected record changed successfully.';
	header("location:manageuser.php");
	}
}


	
if(@$_REQUEST['Delete']!='')
{
	$del = new db;
	$del->query("DELETE FROM  tbluser WHERE uId = '".base64_decode($_REQUEST['Delete'])."'");
	$_SESSION['sess_msg'] = 'Selected record deleted successfully.';
	header("location:manageuser.php");
}

if(@$_REQUEST['alphabet']!='')
{
	$sql2.= " AND userName LIKE '". $_GET[alphabet] ."%'";
}
if(@$_REQUEST['status']!='')
{
	@$sql2.= " AND status = '".$_REQUEST[status]."'";
}

if(@$_REQUEST['user']!='')
{
	@$sql2.= " AND  userName LIKE '%".$_REQUEST['user']."%'";
}		
		
$start=0;
if(isset($_GET['start'])) $start=$_GET['start'];

if(@$_GET['pagesize']!=''){$_SESSION['Page'] = $_REQUEST['pagesize'];}
if(@$_SESSION['Page']!=''){	$pagesize = $_SESSION['Page'];}	else{$pagesize = 10;}


$column="select * ";
$sql="from  tbluser where 1=1 ".@$sql2." order by uId desc";
$sql1="select count(*) ".$sql;
$sql=$column.$sql;
$sql.=" limit $start, $pagesize ";
$result=executequery($sql);
$reccnt=getSingleResult($sql1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en" />
<meta name="robots" content="noindex,nofollow" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/reset.css" />
<!-- RESET -->
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/main.css" />
<!-- MAIN STYLE SHEET -->
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/2col.css" title="2col" />
<!-- DEFAULT: 2 COLUMNS -->
<link rel="alternate stylesheet" media="screen,projection" type="text/css" href="css/1col.css" title="1col" />
<!-- ALTERNATE: 1 COLUMN -->
<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="css/main-ie6.css" /><![endif]-->
<!-- MSIE6 -->
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/style.css" />
<!-- GRAPHIC THEME -->
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/mystyle.css" />
<!-- WRITE YOUR CSS CODE HERE -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.switcher.js"></script>
<script type="text/javascript" src="js/toggle.js"></script>
<script type="text/javascript" src="js/ui.core.js"></script>
<script type="text/javascript" src="js/ui.tabs.js"></script>
<link href="../css/calander.css"  rel="stylesheet" type="text/css" />
<title><?=ADMINTITLE?></title>
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
<script type="text/javascript">
function checkall(form1)
{
len = document.inv_frm.elements.length;
for(var i=0; i<len; i++)
{
if(document.inv_frm.elements[i].type=='checkbox')	document.inv_frm.elements[i].checked=document.inv_frm.check_all.checked;

}
}
function del_prompt(inv_frm,comb)
{
    var count=0;
	var len = document.inv_frm.elements.length;
	var i=0;
	for(i=0; i<len; i++)
	{
	if(document.inv_frm.elements[i].type=='checkbox' && document.inv_frm.elements[i].checked==true)
	count++;
	}
	if(count <= 0)
	{
	alert('Please check at least one checkbox.');
	return false;
	}
	

}
</script>
</head>
<body>
<div id="main">
  <!-- Tray -->
  <? include("header.php");?>
  <!--  /tray -->
  <!-- Menu -->
  <!-- /header -->
  <hr class="noscreen" />
  <!-- Columns -->
  <div id="cols" class="box">
    <!-- Aside (Left Column) -->
    <? include("left.php");?>
    <!-- /aside -->
    <hr class="noscreen" />
    <!-- Content (Right Column) -->
    <div id="content" class="box">
      <h1>Manage Users<span style="padding-left:600px;">Total :
        <?=$reccnt?>
        </span></h1>
      <div class="fix"></div>
      <form name="a_frm" id="a_frm" action="" method="get">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <? if(@$_SESSION['sess_msg']!=''){?>
          <tr>
            <td align="center" class="msg info"><?=$_SESSION['sess_msg']?>
              <? $_SESSION['sess_msg']='';?></td>
          </tr>
          <? }?>
          <tr>
            <td align="center"><table width="715" border="0" cellspacing="1" cellpadding="4"  bgcolor="#D8D8D8">
                <tr >
                  <td height="25" colspan="4" align="left"  class="header_bg">Search Record in Table</td>
                </tr>
                <tr bgcolor = "#FFFFFF">
                  <td width="128" align="right" class="htext">Name</td>
                  <td width="196"><input name="user" type="text" class="input-text"  id="user" value="<?=@trim($_REQUEST['user'])?>" size="35"/></td>
                  <td width="92" align="left">STATUS </td>
                  <td width="258" align="left"><select name="status" class="select">
                      <option value="" >-Select Status-</option>
                      <option value="Y" <? if(@$_REQUEST['status']=='Y'){?> selected="selected"<? }?>>Active</option>
                      <option value="N" <? if(@$_REQUEST['status']=='N'){?> selected="selected"<? }?>>Inactive</option>
                    </select></td>
                </tr>
                <tr bgcolor = "#FFFFFF">
                  <td colspan="4" align="center" ><input type="submit" name="search" value="Search" class="input-submit"></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><?	include("num_page.php"); ?></td>
          </tr>
          <tr>
            <td align="right"><a href="addedituser.php">Add User</a></td>
          </tr>
          <tr>
            <td align="center"><strong> Search by Alphabet &nbsp;&nbsp;&nbsp;&nbsp; <a href="<?=$_SERVER[PHP_SELF]?>" class="nonalph">All</a> -
              <?
                                                        for ($i=65;$i<91;$i++) 
                                                        {
                                                    ?>
              - <a  href="<?=@$_SERVER[PHP_SELF]?>?alphabet=<?=chr($i)?>" <? if(@$_GET['alphabet']==chr($i)){?>class="alph"<? }else{?>class="nonalph"<? }?>>
              <?=chr($i)?>
              </a>
              <? } ?>
              </strong></td>
          </tr>
        </table>
      </form>
      <form name="inv_frm" action="" method="post">
        <table class="tablesorter" width="100%">
          <!-- Sort this TABLE -->
          <thead>
            <? if($reccnt<=0){?>
            <tr>
              <td colspan="11" align="left" class="msg error" style="padding-left:40px;">Sorry ! Record not found.</td>
            </tr>
            <? }else{?>
            <tr>
              <th width="4%"><span class="blacktext">
                <input name="check_all" type="checkbox"  onclick="checkall(this.form);" value="check_all"/>
                </span></th>
              <th width="4%">S.N.</th>
              <th width="9%">Name</th>
              <th width="17%">Email</th>
              <th width="13%">Account Number</th>
              <th width="17%">Reg. Date</th>
              <th width="7%">Status</th>
              <th width="7%">Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="11" class="width100"><? include("paging.inc.php");?></td>
            </tr>
            <tr class="bg">
              <td colspan="11" class="arrow-01"><select name="action" class="select" id="action">
                  <option value="Delete">Delete Account</option>
                  <option value="Active">Active Account</option>
                  <option value="Inactive">InActive Account</option>
                </select>
                &nbsp;
                <input name="Submit" type="submit" value="OK" onClick="return del_prompt(this.form,this.value);"/></td>
            </tr>
          </tfoot>
          <tbody>
            <?
					$k=1;
					while($at=mysql_fetch_array($result)){?>
            <tr>
              <td class="t-left"><input type="checkbox" name="ids[]" value="<?=@$at['uId']?>"></td>
              <td class="t-left"><? if(@$_REQUEST['start']==''){echo $k++;}else{echo $_REQUEST['start']+$k++;}?></td>
              <td class="t-left"><?=$at['userName'];?></td>
              <td class="t-left"><?=stripslashes($at['userEmail'])?></td>
              <td class="t-left"><?=stripslashes($at['UserAccountNumber'])?></td>
              <td class="t-left"><?=mydate(strtotime($at['timestamp']))?></td>
              <td class="t-left"><? if($at['status']=='Y'){echo "Active";}else{echo "InActive";}?></td>
              <td class="t-center"><a href="addedituser.php?Edit=<?=base64_encode($at['uId'])?>"><img src="design/ico-edit.gif" class="ico" alt="Edit" /></a>&nbsp;&nbsp; <a href="manageuser.php?Delete=<?=base64_encode($at['uId'])?>" onClick="return confirm('Do You really Want To Delete This?');"><img src="design/ico-delete.gif" class="ico" alt="Delete"/></a></td>
            </tr>
            <? }?>
            <? }?>
          </tbody>
        </table>
      </form>
    </div>
    <!-- /content -->
  </div>
  <!-- /cols -->
  <hr class="noscreen" />
  <!-- Footer -->
  <? include("footer.php");?>
  <!-- /footer -->
</div>
<!-- /main -->
</body>
</html>
