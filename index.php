<?php
require_once('common/inc/global.inc.php');
require_once(_CLS_PATH . "/Utility.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: Lev Mobile ::</title>
<link href="css/styles.css" rel="stylesheet" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/easySlider1.7.js"></script>
<script type="text/javascript">
		$(document).ready(function(){	
			$("#slider").easySlider({
				auto: true, 
				continuous: true
				
			});
		});	
</script>
<script type="text/javascript">
function removeerrors(errorr)
{
	document.getElementById(errorr).innerHTML = "";
}

function funvalidation()
 {
 	var aa=1;
	var errortofocus="";
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var phoneRegEx =/^[0-9]+$/i;
	var num_patt=/^[0-9]$/i;	
	var chkFocus = false;
	//------------------  Email  -------------------//	
	if(document.getElementById("email").value=="" && document.getElementById("email").value=="Enter your Email for Updates")
	{
		document.getElementById('emailerr').innerHTML = "*Please check your mail address.";
		if(chkFocus==false)
		errortofocus='email';
		aa=0;	
		chkFocus = true;			
	}
	else if(document.getElementById("email").value.search(emailRegEx) == -1)
	{
		document.getElementById('emailerr').innerHTML = "*Please enter a valid email address.";
		if(chkFocus==false)
		errortofocus='email';
		aa=0;
		chkFocus = true;
	} 

		
 	if(aa==0)
	{
		document.getElementById(errortofocus).focus();
		return false;
	}
	else
	{
		return true;
	}
}
</script>
</head>

<body>
<div class="wrapper">
	<div class="header">
    	<div class="logo"><a href="index.html" title="Lev Mobile"><img src="images/logo.png" alt="Logo" /></a></div>
        
      <div class="header_right">
       	  <!--Icon Start-->
        	<div class="social-icons">
            	<span><a href="#"><img src="images/in.png" alt="Linkedin" title="Linkedin" /></a></span>
            	<span><a href="#"><img src="images/twit.png" alt="Twitter" title="Twitter" /></a> |</span>
                <span><a href="#"><img src="images/fb.png" alt="Facebook" title="Facebook" /></a> |</span>
          	</div>
            <!--Icon end-->
            <ul>
            	<li><a class="active" href="#" title="About us" >About us</a></li>
                <li><a href="#" title="FAQ'S" >FAQ's</a></li>
                <li><a href="#" title="Terms of Use" >Terms of Use</a></li>
                <li><a href="contact.php" title="Contact Us" >Contact Us</a></li>
            </ul>
   	 </div>
      <div class="cl"></div>      
    </div><!--header end-->
    
    
    <div class="content">
      <div class="topbanner">
      	<div class="leftimg"><img src="images/iphone.png" align="Iphone" /></div>
        <div class="rightifo">
        	<h1>Empower Your Mobile Devices.</h1>
            <p>The iPhone app enables affordable Global Voice Call and Global SMS messaging from your Wi-fi or data connection.</p>
            <p>Enable your iPad to communicate just like your phone.</p>
            <p>(Please enter your email address for more details) (Place inside the box)</p>
            <div class="cl"></div> 
            <span id="emailerr" style="color:#FF0000; "></span> <? if(@$_SESSION['sess_msg']!=''){?><span style="color:#FF0000; "><?=@$_SESSION['sess_msg']?><? @$_SESSION['sess_msg']='';?></span><?php } ?>
            <div class="form">
             <form method="post" action="newslettercode.php">
            	<input type="text" class="inputbox" name="email" id="email" value="Enter your Email for Updates" onfocus="javascript:if(this.value=='Enter your Email for Updates')this.value='';" onblur="javascript:if(this.value=='')this.value='Enter your Email for Updates';" />
                
                <input type="submit" name="submit" id="submit" value="OK" class="sbtn" onClick="return funvalidation();"  />
                </form>
            </div>
            <span class="app-store"><a href="#"><img src="images/app-bnnr.jpg" alt="App Store" title="App Store" /></a></span>
        </div><!--rightifo end-->
        <div class="cl"></div>      
      </div><!--topbanner end-->
     <div class="slider_outer"> 
      <div id="slider">
			<ul>				
				<li>
                	<div class="imagebox"><img src="images/slider-image1.png" /></div>
                    <p>A simple communication app that lets you call and text family and friends around the world.</p>
                </li>
                <li>
                	<div class="imagebox"><img src="images/slider-image1.jpg" /></div>
                    <p>Give your iPad a telephone number to make and receive global calls and SMS on your tablet.  </p>
                </li>
							
			</ul>
		</div><!--slider end-->
      </div>
      
      
    </div><!--content end-->
</div><!--wrapper end-->

<div class="footer_outer">
	<div class="footer"><p>&copy; Copyright 2012 Emet Corporation. All rights reserved. Terms, conditions,<br /> features, availability, pricing, fees, service and support options subject to change without notice.<br /> iPad and iPhone are trademarks of Apple Inc., registered in the U.S. and other countries.</p></div>
</div><!--footer outer end-->


</body>
</html>
