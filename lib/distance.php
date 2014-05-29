<?php
	require_once "include.php";
	//returns the shortest road route betwee the two latitudes
	function dist_between_geo($geo1,$geo2){
		$lat=$geo1[0];
		$long=$geo1[1];
		$destlat=$geo2[0];
		$destlong=$geo2[1];
		$theta = $long - $destlong;
		$dist = sin(deg2rad($lat)) * sin(deg2rad($destlat)) +  cos(deg2rad($lat)) * cos(deg2rad($destlat)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = $dist*6371;
		//the distance to the nearest place in km
        return round($dist, 1);
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
        	//echo $d->status;
        	return false;
        }
        $closest =  $d->results[0];
		//echo "NAME".$closest->name;
		$endloclat=$closest->geometry->location->lat;
		$endloclng=$closest->geometry->location->lng;
		return array("geo"=>array($endloclat,$endloclng),"name"=>$closest->name,"data"=>json_encode($closest));
	}
	function get_all_results_dist($postcode,$type,$names,$lat,$lng){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/search/json";
		$argstr="?types=".$type."&sensor=false&key=".GOOGLE_API_KEY;
		$argstr.="&location=".$lat.",".$lng."&rankby=distance&name=".$names;
		logmsg("proximity-lib",$url.$argstr);
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data);
		
        //var_dump($d);
        return $d->results;
	}
	function get_nearest_result($postcode,$type,$names,$lat,$lng){
        $d=get_all_results_dist($postcode,$type,$names,$lat,$lng);
        if (count($d) < 1) {
            return FALSE;
        }
        
		//gets the lat and long of the first result
		$endloclat=$d[0]->geometry->location->lat;
		$endloclng=$d[0]->geometry->location->lng;
		return array("geo"=>array($endloclat,$endloclng),"name"=>$d[0]->name,"data"=>json_encode($d));
	}
	function dist_to_result($postcode,$type,$names,$lat,$lng){
		$nearest_of_type=get_nearest_result($postcode,$type,$names,$lat,$lng);
        if ($nearest_of_type === FALSE) {
            return FALSE;
        }
        
		$nearest_lat_lng=$nearest_of_type["geo"];
        return dist_between_geo(array($lat,$lng), $nearest_lat_lng);
	}

?>
