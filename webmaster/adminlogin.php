<?php
if((($_SERVER['REQUEST_METHOD']=='POST') && ($_POST['admin_pass']!='') && ($_POST['admin_user']!='')))
{
  $db = new db;
  $db->query("select admin_id,admin_name from tbladmin where admin_name = '".addslashes($_POST['admin_user'])."' and admin_pass = '".addslashes($_POST['admin_pass'])."'");
   $num = count($db->arr);
   if($num!='0')
	{
		if($_POST['Remain']=='Y')
		{
			setcookie("AdminUser", $_POST['admin_user'], time() + 86400);  /* expire in 1 hour */
			setcookie("AdminPass", $_POST['admin_pass'], time() + 86400);
		}
		$_SESSION['AdminId'] = $db->rs['admin_id'];
    	$_SESSION['admin_name'] = $db->rs['admin_name'];
		$db->query("update tbladmin set admin_time = '".time()."' where admin_id = '".$_SESSION['AdminId']."'");
		header("location:dashboard.php");
	}
	else
	{
		$_SESSION['msg_err2'] = 'Sorry ! username and password invalid.';
	}
}

if(($_SERVER['REQUEST_METHOD']=='POST') && ($_POST['send']=='Send'))
{
	if($_POST['admin_email']!='')
	{
			$f=new db;
			$f->query("select * from tbladmin where admin_email = '".$_POST['admin_email']."'");
			$num_data = count($f->arr);
				if($num_data=='0')
				{
					$_SESSION['msg_err'] = 'Sorry ! your email address invalid.';
				}
				else
				{
					$_SESSION['msg_err'] = 'Your password mailed on your email address';
					$to = $_POST['admin_email'];
					$subject = 'Your Admin Information';
					$msg1 .="Here is your Username and Password that you requested<br><br>";
					$msg1 .="Your username is : ".$f->rs['admin_name']."<br><br>";
					$msg1 .="Your password is : ".$f->rs['admin_pass']."";
					$message = "<html><head></head><body>".stripslashes(nl2br($msg1))."</body></html>";
					$headers  .= "MIME-Version: 1.0\r\n"; 
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
					$headers .= 'From: '.$_POST['admin_email'].''."\r\n";
					'Reply-To: '.$_POST['admin_email'].''. "\r\n" ;
					mail($to, $subject, $message, $headers);
				}
		}else
		{
			$_SESSION['msg_err'] = 'Please enter your email address';
		}
}
?>