<?php
ob_start();
session_start();
include("includes/config/db.php");
include("includes/functions/functions.php");
include("sess_check.php");
$db = new db;
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

			<h1>Dashboard</h1>

			

			<h3>Quick Links</h3>

		<div class="btn-box">
			<div class="btn-top"></div>
			<div class="btn">
				<dl>
					<dt><a href="adminsetting.php">Admin Setting</a></dt>
				  <dd>Admin management</dd>
				</dl>
			</div> <!-- /btn -->
            
			<div class="btn-bottom"></div>            
			</div>
            <div class="btn-box">
			<div class="btn-top"></div>
			<div class="btn">
				<dl>
					<dt><a href="manageuseremail.php">Manage newsletter user</a></dt>
				  <dd>User Management</dd>
				</dl>
			</div> <!-- /btn -->
			<div class="btn-bottom"></div>
			</div>
        </div>
		<!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<? include("footer.php");?>
    <!-- /footer -->

</div> <!-- /main -->

</body>
</html>
