<div id="tray" class="box">
  <p class="f-left box"> 
    <!-- Switcher --> 
    <span class="f-left" id="switcher"> <a href="javascript:;" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="design/switcher-1col.gif" alt="1 Column" /></a> <a href="javascript:;" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="design/switcher-2col.gif" alt="2 Columns" /></a></span> Project:<strong>
    <?=ADMINTITLE?>
    </strong></p>
  <p class="f-right">User: <strong><a href="adminsetting.php">
    <?=ucfirst($_SESSION['admin_name'])?>
    </a></strong> &nbsp;&nbsp;&nbsp;<strong><a href="logout.php" id="logout">Log out</a> </strong></p>
</div>
<!--  /tray -->
<hr class="noscreen" />
<!-- Menu -->
<div id="menu" class="box">
  <ul class="box f-right">
    <li>&nbsp;</li>
  </ul>
  <ul class="box">
    <li <? if(strstr($_SERVER['REQUEST_URI'],"dashboard.php")){?>id="menu-active"<? }?> onmouseover="this.className = 'dropdown-on'" onmouseout="this.className = 'dropdown-off'">
      <div><a href="dashboard.php"><span>Dashboard</span></a> </div>
    </li>
    <li <? if(strstr($_SERVER['REQUEST_URI'],"adminsetting.php")){?>id="menu-active"<? }?>><a href="adminsetting.php"><span>Admin</span></a></li>
    <li <? if((strstr($_SERVER['REQUEST_URI'],"manageuseremail.php"))) {?>id="menu-active"<? }?> onmouseover="this.className = 'dropdown-on'" onmouseout="this.className = 'dropdown-off'">
      <div><a href="manageuseremail.php"><span>User Email</span></a> </div>
    </li>
  </ul>
</div>
<!-- /header -->