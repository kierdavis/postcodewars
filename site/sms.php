<?php
<<<<<<< HEAD
<<<<<<< HEAD
=======
//Debugging
error_reporting(-1);

=======
<<<<<<< HEAD
>>>>>>> f6c759631f65d7acfb0b4448c78becfbbcfe0e85
=======
//Debugging
error_reporting(-1);

>>>>>>> c474b309234935037f4fdd80913b3ceeb9c77e87
<<<<<<< HEAD
=======
>>>>>>> f754d8779ea9b2d6ca8db9fe3ef08beccdcf551f
>>>>>>> f6c759631f65d7acfb0b4448c78becfbbcfe0e85
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
	
	// Yay, I wrote a regex. I probably ought to test this.
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> f6c759631f65d7acfb0b4448c78becfbbcfe0e85
	$myInputRegex = "/^((([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})) +(([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})))$/";
	if (!preg_match($myInputRegex, $incoming)) {
		$message = "Input not valid. It should look like this: 'NG11AA LE11AA'";
	}
	else {
		// Split the two postcodes up
		$postcodes = explode(" ", $incoming);
=======
>>>>>>> f754d8779ea9b2d6ca8db9fe3ef08beccdcf551f
	// This first one only matches AA11AA and AA111AA
	// $myInputRegex = "/^((([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})) +(([a-zA-Z]{1,2})(([0-9]{1,2})|(([0-9])([a-zA-Z])))([0-9]{1})([a-zA-Z]{2})))$/";
	// This next one should match the following:
	//	-AA11AA
	//	-AA111AA
	//	-AA1A1AA
	//	-and correctly spaced postcodes. And a mix. Much better!
	$myInputRegex = "/^((([a-zA-Z]{2})([0-9]{1,2})([a-zA-Z]?)(\s?))([0-9]{1})([a-zA-Z]{2}))$/";
	if (!preg_match($myInputRegex, $incoming, $postcodes)) {
		$message = "Oops! They don't look like postcodes to me.";
	}
	else {
		// Split the two postcodes up
<<<<<<< HEAD
>>>>>>> c474b309234935037f4fdd80913b3ceeb9c77e87
=======
<<<<<<< HEAD
=======
>>>>>>> c474b309234935037f4fdd80913b3ceeb9c77e87
>>>>>>> f754d8779ea9b2d6ca8db9fe3ef08beccdcf551f
>>>>>>> f6c759631f65d7acfb0b4448c78becfbbcfe0e85
		$pc1 = $postcodes[0];
		$pc2 = $postcodes[1];
		
		// Search the postcodes and return scores!
		$result = search($pc1, $pc2);
		$score1 = $result['_score1'];
		$score2 = $result['_score2'];
		
		// A quick function to split postcodes (or return the unsplit one in some cases) to avoid repetition:
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
		}
		
		// Now do the actual splitting
		$pc1_split = strtoupper(pc_split($pc1));
		$pc2_split = strtoupper(pc_split($pc2));
		
		// Compare the scores and write an appropriate message
		if ($score1 > $score2) { $message = $pc1_split . " wins, " . $score1 . "-" . $score2 . "!"; }
		elseif ($score1 < $score2) { $message = $pc2_split . " wins, " . $score2 . "-" . $score1 . "!"; }
		else { $message = "It was a draw!"; }
	}
	
	// Now output the message as TwiML!
?>
	<Response>
	    <Sms><?php echo $message; ?></Sms>
	</Response>