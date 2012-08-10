<?php
	$message = $_REQUEST['From'] . "\r\n" . $_REQUEST['To'] . "\r\n" . $_REQUEST['Message'];
	mail("jacob.walker94@gmail.com", "Twilio tester", print_r($_REQUEST), "From: twilio-test@androidbanana.co.uk");
?>