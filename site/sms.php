<?php
	$message = $_REQUEST['From'] . "\r\n" . $_REQUEST['To'] . "\r\n" . $_REQUEST['Body'];
	mail("jacob.walker94@gmail.com", "Twilio tester", $message, "From: twilio-test@androidbanana.co.uk");
?>