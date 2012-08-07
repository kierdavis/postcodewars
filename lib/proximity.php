<?php
	$googlekey="AIzaSyAlxfcq-vCtqP4Z7xjPJTHgWzX1T17TlfU";
	//gets the proximity to the nearest A&E department using Google places API
	function get_ae_proximity($db,$postcode,$lat,$lng){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/textsearch/json";
		$argstr="?query=a%26e+near+".$postcode."&sensor=false&key=".$googlekey;
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data, true);
		
		//gets the lat and long of the first result
		$endloclat=$d->results[0]->geometry->location->lat;
		$endloclng=$d->results[0]->geometry->location->lat;
		echo $endloclat . "," . $endloclng;
	}
?>