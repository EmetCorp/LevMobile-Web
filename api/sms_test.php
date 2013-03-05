<?php
require('Services/Twilio.php');

$client = new Services_Twilio('AC78689f5b95066c13cd60213da3901e99', 'e72f490c6e338466a46ec5c6ee2b295e');
$message = $client->account->sms_messages->create(
  '+16465536390', // From a Twilio number in your account
  '+19084873577', // Text any number
  "Hello testing!"
);

print $message->sid;

?>