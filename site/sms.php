<?php
	// For debugging - remove this when you're done
	//error_reporting(E_ALL);
	
	// Just to check this is working
	//echo $_REQUEST['Body'];
	
	// Requiring search's requires
    require_once "../lib/include.php";
    require_once "../lib/util.php";
    
	// Requiring the search interface
    require_once "../lib/search.php";
    
	// This is the text message sent to us
	$incoming = $_REQUEST['Body'];
	
	// Yay, I wrote a regex. I probably ought to test this.
	$myInputRegex = "/^((([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})) +(([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})))$/";
	if (!preg_match($myInputRegex, $incoming)) {
		// Debugging
		//echo "Preg match failed";
		//goto eof;
		
		$message = "Input not valid. It should look like this: 'NG11AA LE11AA'";
	}
	else {
		// Debugging
		//echo "Preg match succeeded!";
		
		// Split the two postcodes up
		$postcodes = explode(" ", $incoming);
		$pc1 = $postcodes[0];
		$pc2 = $postcodes[1];
		
		// Debugging
		//echo " ... " . $pc1 . ", " . $pc2;
		
		// Search the postcodes and return scores!
		$result = search($pc1, $pc2);
		$score1 = $result['_score1'];
		$score2 = $result['_score2'];
		
		// Debugging
		//echo " ... " . $score1 . ", " . $score2;
		
		// Compare the scores and write an appropriate message
		if ($score1 > $score2) { $message = $pc1 . " wins, " . $score1 . "-" . $score2 . "!"; }
		elseif ($score1 < $score2) { $message = $pc2 . "wins, " . $score2 . "-" . $score1 . "!"; }
		else { $message = "It was a draw!"; }
		
		// Debugging
		#var_dump($message);
	}
	
	// Send the XML header
    header("content-type: text/xml");
	
	// Output the XML content-type tag (this has to be echoed, otherwise PHP thinks it's PHP)
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	
	// Now output the message as TwiML!
?>
	<Response>
	    <Sms><?php echo $message; ?></Sms>
	</Response>
<?php
	eof:
?>