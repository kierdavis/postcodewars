<?php
	//gets the proximity to the nearest A&E department using Google places API
	require_once "include.php";
	function get_all_results($postcode,$criteria,$type,$lat,$lng,$radius,$rankbydist){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/search/json";
		$argstr="?types=".$type."&sensor=false&key=".GOOGLE_API_KEY;
		$argstr.="&location=".$lat.",".$lng;
		if(isset($rankbydist)) {
			$argstr.="&rankby=distance";
		}
		else{
			$argstr.="&radius=".$radius;
		}
		logmsg("proximity-lib",$url.$argstr);
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data);
		
        //var_dump($d);
        return $d->results;
	}
?>
