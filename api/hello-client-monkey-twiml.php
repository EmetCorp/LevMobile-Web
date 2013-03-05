<?php
header('Content-type: text/xml');

//$token->allowClientOutgoing('APa1b6fe6c0c76495e9a5a9fcafe277c21');

// put a phone number you've verified with Twilio to use as a caller ID number
//$callerId = "+16467450935";

$callerId = "+16465767457";

//$_REQUEST["PhoneNumber"] = "+1415-599-2671";
 ?>
<Response>
<Dial callerId="<?php echo $callerId ?>">
<Number><?php echo $_REQUEST["PhoneNumber"]; ?></Number>
</Dial>
</Response>