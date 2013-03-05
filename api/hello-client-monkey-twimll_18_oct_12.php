<?php
header('Content-type: text/xml');
$number = $_REQUEST["PhoneNumber"];
//$number = '+919555367533';//.$_REQUEST["PhoneNumber"];
// put a phone number you've verified with Twilio to use as a caller ID number
//$callerId = "+16467450935";
/* Commented on 09 Oct 12
$callerId = "+16467450935";*/
$callerId = $_REQUEST["callerId"]; /* +18583751825 Added on 09 Oct 12 */

?>
<Response>
<Dial callerId="<?php echo $callerId ?>">
<?php if (preg_match("/^\d+$/", $number)) { ?>
<Number><?php echo $number;?></Number>
<?php } else { ?>
<Client><?php echo $number;?></Client>
<?php } ?>
</Dial>
</Response>