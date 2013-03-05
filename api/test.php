<?php
	
	/*$URL = "https://api.twilio.com/2010-04-01/Accounts/AC78689f5b95066c13cd60213da3901e99/SMS/Messages.xml";*/
	/*$URL = "https://api.twilio.com/2010-04-01/Accounts/AC78689f5b95066c13cd60213da3901e99/SMS/Messages.xml";

	$xml_data = "?From=+16465536390&To=+16465767457&Body=Hello&AC78689f5b95066c13cd60213da3901e99:e72f490c6e338466a46ec5c6ee2b295e";
	$ch = curl_init($URL);*/
	//curl_setopt($ch, CURLOPT_MUTE, 1);
	/*curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");*/
	/*curl_setopt($ch, CURLOPT_GETFIELDS, "$xml_data");	*/
/*	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	
	echo "Output=><br/>";
	echo $output;
	echo "<pre/>";
	print_r($output);*/



/*https://api.twilio.com/2010-04-01/Accounts/AC78689f5b95066c13cd60213da3901e99/SMS/Messages.xml' \
-u AC78689f5b95066c13cd60213da3901e99:{AuthToken}
e72f490c6e338466a46ec5c6ee2b295e

curl -X GET 'https://api.twilio.com/2010-04-01/Accounts/AC78689f5b95066c13cd60213da3901e99/SMS/Messages.xml?From=%2B19175254838' \
-u AC78689f5b95066c13cd60213da3901e99:{AuthToken}
*/

/*  
-u AC78689f5b95066c13cd60213da3901e99:{AuthToken}
	POST /2010-04-01/Accounts/AC5ef87.../SMS/Messages.Xml
    From=+14158141829&To=+14159352345&Body=Jenny%20please%3F%21%20I%20love%20you%20%3C3

"curl -X POST 'https://api.twilio.com/2010-04-01/Accounts/AC78689f5b95066c13cd60213da3901e99/SMS/Messages.xml' \
-d 'From=%2B14155992671' \
-d 'To=%2B16465767457' \
-d 'Body=Jenny+please%3F%21+I+love+you+%3C3' \
-u AC78689f5b95066c13cd60213da3901e99:{AuthToken}";

$xml_data ='<AATAvailReq1>'.
	'<Agency>'.
		'<Iata>1234567890</Iata>'.
		'<Agent>lgsoftwares</Agent>'.
		'<Password>mypassword</Password>'.
		'<Brand>phpmind.com</Brand>'.
	'</Agency>'.
	'<Passengers>'.
		'<Adult AGE="" ID="1"></Adult>'.
		'<Adult AGE="" ID="2"></Adult>'.
	'</Passengers>'.
'<HotelAvailReq1>'.
'<DestCode>JHM</DestCode>'.
		'<HotelCode>OGGSHE</HotelCode>'.
		'<CheckInDate>101009</CheckInDate>'.
		'<CheckOutDate>101509</CheckOutDate>'.
		'<UseField>1</UseField>'.
  '</HotelAvailReq1>'.
  '</AATAvailReq1>';
$URL = "https://www.yourwebserver.com/path/";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			<form action="sms-rest.php" method="POST">	*/	
			
//echo md5("adminWe!Ref!");	


// Download/Install the PHP helper library from twilio.com/docs/libraries.
// This line loads the library
require ($_SERVER['DOCUMENT_ROOT']."/api/Services/Twilio.php");

// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC78689f5b95066c13cd60213da3901e99"; 
$token = "e72f490c6e338466a46ec5c6ee2b295e"; 
$client = new Services_Twilio($sid, $token);
 
// Get an object from its sid. If you do not have a sid,
// check out the list resource examples on this page
$account = $client->accounts->get("AC73ff52204ca2d8e1e103fe551054efd5");
$account->update(array(
        "Status" => "closed"
    ));
echo $account->friendly_name;

?>

<!--<form action="hello-client-monkey-twimll.php" method="GET">
From:
<input name="fromNo" type="text" />
To:
<input name="toNo" type="text" />
msg:
<input name="msg" type="text" />

PhoneNumber:
<input name="PhoneNumber" type="text" />
callerId
<input name="callerId" type="text" />
<input name="" type="submit" />-->
</form>

