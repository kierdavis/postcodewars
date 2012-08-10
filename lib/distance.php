<?php
	require_once "include.php";
	//returns the shortest road route betwee the two latitudes
	function dist_between_geo($geo1,$geo2){
		$c = curl_init();
		$url="https://maps.googleapis.com/maps/api/directions/json";
		$destlat=$geo2[0];
		$destlng=$geo2[1];
		$lat=$geo1[0];
		$lng=$geo1[1];
		$argstr="?sensor=false&origin=".$lat.",".$lng."&destination=".$destlat.",".$destlng;
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data);
        echo $url.$argstr;
        if ($d->status != "OK") {
            if ($d->status == "ZERO_RESULTS") {
                return FALSE;
            }
            
            throw new Exception("API call to Google Maps Directions returned: " . $d->status);
        }
        
        $no_of_legs = count($d->routes[0]->legs);
		//the distance to the nearest place in km
        return round($d->routes[0]->legs[0]->distance->value, 1);
	}
	function get_first_by_text_search($postcode,$placetype){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/textsearch/json";
		$argstr="?query=".$placetype." loc: ".$postcode."&sensor=false&key=".GOOGLE_API_KEY;
		//echo $url.$argstr;
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data);
		
        //var_dump($d);
        if($d->status!="OK"){
        	return false;
        }
        $closest =  $d->results[0];
		$endloclat=$closest->geometry->location->lat;
		$endloclng=$closest->geometry->location->lng;
		return array("geo"=>array($endloclat,$endloclng),"name"=>$closest->name,"data"=>json_encode($closest));
	}
	function get_all_results_dist($postcode,$type,$lat,$lng){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/search/json";
		$argstr="?types=".$type."&sensor=false&key=".GOOGLE_API_KEY;
		$argstr.="&location=".$lat.",".$lng."&rankby=distance";
		logmsg("proximity-lib",$url.$argstr);
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data);
		
        //var_dump($d);
        return $d->results;
	}
	function get_nearest_result($postcode,$type,$lat,$lng){
        $d=get_all_results_dist($postcode,$type,$lat,$lng);
        if (count($d) < 1) {
            return FALSE;
        }
        
		//gets the lat and long of the first result
		$endloclat=$d[0]->geometry->location->lat;
		$endloclng=$d[0]->geometry->location->lng;
		return array("geo"=>array($endloclat,$endloclng),"name"=>$d[0]->name,"data"=>json_encode($d));
	}
	function dist_to_result($postcode,$type,$lat,$lng){
		$nearest_of_type=get_nearest_result($postcode,$type,$lat,$lng);
        if ($nearest_of_type === FALSE) {
            return FALSE;
        }
        
		$nearest_lat_lng=$nearest_of_type["geo"];
		//do google dist calc using API
        return dist_between_geo(array($lat,$lng), $nearest_lat_lng);
	}
?>