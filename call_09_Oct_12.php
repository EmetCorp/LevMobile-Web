<?php
session_start();
if($_SESSION['userId']==''){ header('Location:login.php?msg=expired');	exit(); }
include 'api/Services/Twilio/Capability.php';
// put your Twilio API credentials here
$accountSid = "AC78689f5b95066c13cd60213da3901e99";
$authToken = "e72f490c6e338466a46ec5c6ee2b295e";
// put your Twilio Application Sid here
$appSid = "AP1ef0a34136d04f818502f447e2fc2576";
$capability = new Services_Twilio_Capability($accountSid, $authToken);
$capability->allowClientOutgoing($appSid);
$capability->allowClientIncoming('jenny');
$token = $capability->generateToken(86400);
?>
<!DOCTYPE html>
<html>
<head>
<title>Twilio Client</title>
<script type="text/javascript"
src="http://static.twilio.com/libs/twiliojs/1.0/twilio.min.js"></script>
<script type="text/javascript" src="js/jqueryapi.js"></script>
<link href="css/main.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
Twilio.Device.setup("<?php echo $token; ?>");
Twilio.Device.ready(function (device) {
$("#log").text("Ready");
});
Twilio.Device.error(function (error) {
$("#log").text("Error: " + error.message);
});
Twilio.Device.connect(function (conn) {
$("#log").text("Successfully established call");
});
Twilio.Device.disconnect(function (conn) {
$("#log").text("Call ended");
});
Twilio.Device.incoming(function (conn) {
$("#log").text("Incoming connection from " + conn.parameters.From);
// accept the incoming connection and start two-way audio
conn.accept();
});
function removeerrors(errorr)
{
	document.getElementById(errorr).innerHTML = "";
}
function call() 
{
	// get the phone number to connect the call to
	var aa=1;
	var errortofocus="";
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var phoneRegEx =/^[0-9]+$/i;
	var num_patt=/^[0-9]$/i;	
	var chkFocus = false;
	if(document.getElementById("number").value=="" )
	{
		document.getElementById('numbererr').innerHTML = "*Please enter mobile number.";
		if(chkFocus==false)
		errortofocus='number';
		aa=0;
		chkFocus = true;				
	}
	else if(document.getElementById("number").value.search(phoneRegEx) == -1)
	{
		document.getElementById('numbererr').innerHTML = "*Please enter number(Integer) only.";
		if(chkFocus==false)
		errortofocus='number';
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
		params = {"PhoneNumber": $("#number").val()};
		Twilio.Device.connect(params);
		return true;		
	}
}
function hangup() {
Twilio.Device.disconnectAll();
}
</script>

</head>
<body>

<button class="call" onclick="return call();" style="margin-top:240px;"> Call </button>
<button class="hangup" onclick="hangup();"> Hangup </button>
<input type="text" id="number" name="number" placeholder="Enter a phone number to call" onkeydown="removeerrors('numbererr');" />
<span id="numbererr" style="color:red; font-size:14px;" ></span>
<div id="log">Loading ...</div>
<a href="call-log.php" style="text-decoration:none;">
<input type="button" value="Call-Log" style="cursor:pointer;" />
</a> <a href="dashboard.php" style="text-decoration:none;">
<input type="button" value="Cancel" style="cursor:pointer;" />
</a>
</body>
</html>