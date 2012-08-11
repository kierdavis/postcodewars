<?php
// Slightly hacky reshuffle to fix the preceding tab
// Send the XML header
header("content-type: text/xml");
	
// Output the XML content-type tag (this has to be echoed, otherwise PHP thinks it's PHP)
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	
	// Just to check this is working
	//echo $_REQUEST['Body'];
	
	// Requiring search's requires
    require_once "../lib/include.php";
    require_once "../lib/util.php";
    
	// Requiring the search interface
    require_once "../lib/search.php";
    
	// This is the text message sent to us
	$incoming = $_REQUEST['Body'];
	
	// This first one only matches AA11AA and AA111AA
	// $myInputRegex = "/^((([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})) +(([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})))$/";
	// This next one should match the following:
	//	-AA11AA
	//	-AA111AA
	//	-AA1A1AA
	//	-and correctly spaced postcodes. And a mix. Much better!
	$myInputRegex = "/[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]?\s?[0-9]{1}[a-zA-Z]{2}/";
	
	if (!preg_match_all($myInputRegex, $incoming, $postcodes)) {
		$message = "Oops! They don't look like postcodes to me.";
	}
	else {
		//var_dump($postcodes);
		// Split the two postcodes up
		$pc1 = $postcodes[0][0];
		$pc2 = $postcodes[0][1];
		
		// Remove spaces, woo!
		$pc1 = str_replace(" ", "", $pc1);
		$pc2 = str_replace(" ", "", $pc2);
		
		// Debugging
		//var_dump($pc1);
		//var_dump($pc2);
		
		// Search the postcodes and return scores!
		$result = search($pc1, $pc2);
		//var_dump($result);
		$score1 = $result['_score1'];
		$score2 = $result['_score2'];
		//var_dump($result);
		// A quick function to split postcodes (or return the unsplit one in some cases) to avoid repetition:
		/*
		function pc_split($pcvar) {
			if (strlen($pcvar) == 6) {
				$pcvar_split = str_split($pcvar, 3);
				$pcvar_imploded = implode(" ", $pcvar_split);
				return $pcvar_imploded;
			}
			elseif (strlen($pc1) == 7) {
				$pcvar_split = str_split($pcvar, 4);
				$pcvar_imploded = implode(" ", $pcvar_split);
				return $pcvar_imploded;
			}
			else return $pcvar;
		} */
		
		// Now do the actual splitting
		//$pc1_split = strtoupper(pc_split($pc1));
		//$pc2_split = strtoupper(pc_split($pc2));
		
		// Compare the scores and write an appropriate message
		if ($score1 > $score2) {
			$message = $pc1 . " wins, " . $score1 . "-" . $score2 . "!";
		}
		elseif ($score1 < $score2) {
			$message = $pc2 . " wins, " . $score2 . "-" . $score1 . "!";
		}
		else {
			$message = "It was a draw!";
		}
	}
	
	// Now output the message as TwiML!
?>
	<Response>
	    <Sms><?php echo $message; ?></Sms>
	</Response>