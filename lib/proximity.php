<?php
	$googlekey="AIzaSyAlxfcq-vCtqP4Z7xjPJTHgWzX1T17TlfU";
	//gets the proximity to the nearest A&E department using Google places API
	
	function get_nearest_result($postcode,$criteria){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/textsearch/json";
		$argstr="?query=".$criteria."+near+".$postcode."&sensor=false&key=".$googlekey;
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data, true);
		
		//gets the lat and long of the first result
		$endloclat=$d->results[0]->geometry->location->lat;
		$endloclng=$d->results[0]->geometry->location->lat;
		return array("geo"=>array($endloclat,$endloclng),"name"=>$d->results[0]->name,"data"=>json_encode($d->results));
	}
	function dist_to_result($postcode,$criteria,$lat,$lng){
		$nearest_of_type=get_nearest_result($postcode,$criteria);
		$nearest_lat_lng=$nearest_of_type->geo;
		//do google dist calc using API, like below but changed arguments
		
	    /*$c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/textsearch/json";
		$argstr="?query=".$criteria."+near+".$postcode."&sensor=false&key=".$googlekey;
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data, true);*/
        
        //return $d->results[0]->dist;
	}
?>