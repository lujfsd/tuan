<?php
include_once('sendsms.php');

$PhoneNumber = '84918508090';
$ContentSMS = 'Hello, how are you';

doSendMTSpam($PhoneNumber, $ContentSMS);

?>