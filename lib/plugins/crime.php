<?php
require_once "../lib/include.php";

// Function to get force id and neighbourhood code from lat and long 
function getForceAndNhood($lat, $lng) {
	// My unique username and password, woo! The API requires this on every query.
	$userpass = POLICE_API_KEY;
	$url = "http://policeapi2.rkh.co.uk/api/locate-neighbourhood?q=$lat,$lng";

	$curl = curl_init();

	// Gotta put dat password in.
	curl_setopt($curl, CURLOPT_USERPWD, $userpass);
	curl_setopt($curl, CURLOPT_URL, $url);
	
	// Without this, we just get "1" or similar.
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

	$data = curl_exec($curl);

	curl_close($curl);

	// The API returns JSON, and json_decode produces an interesting mix of objects and arrays.
	$dataObj = json_decode($data);
	return $dataObj;
}

// Function to retrieve the bounding area of a neighbourhood
function getNhoodPoly($force, $nhood) {
	
	$userpass = POLICE_API_KEY;
	$url = "http://policeapi2.rkh.co.uk/api/$force/$nhood/boundary";

	$curl = curl_init();

	// Gotta put dat password in.
	curl_setopt($curl, CURLOPT_USERPWD, $userpass);
	curl_setopt($curl, CURLOPT_URL, $url);
	
	// Without this, we just get "1" or similar.
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

	$data = curl_exec($curl);

	curl_close($curl);
	
	$boundaryPoints=json_decode($data);
	
	return $boundaryPoints;
	}
global $crimeDataCache;
$crimeDataCache=[];
function getCrimeRate($lat, $lng, $crimeType) {
	global $crimeDataCache;
	if(array_key_exists($lat.$lng,$crimeDataCache)==FALSE){
		// My unique username and password, woo! The API requires this on every query.
		$userpass = POLICE_API_KEY;
		
		// Getting YYYY-MM of the last year
		$date = new DateTime();
		
		$date->sub(new DateInterval("P12M"));
		
		$dateFormatted = $date->format("Y-m");
		$sendData=[
			"lat"=>$lat,
			"lng"=>$lng,
			"date"=>$dateFormatted
		];
		
		$postFields=http_build_query($sendData);
		$url = "http://policeapi2.rkh.co.uk/api/crimes-street/all-crime?$postFields";
		
		$curl = curl_init();
	
		// Gotta put dat password in.
		curl_setopt($curl, CURLOPT_USERPWD, $userpass);
		curl_setopt($curl, CURLOPT_URL, $url);
		
		// Without this, we just get "1" or similar.
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	
		$data = curl_exec($curl);
	
		curl_close($curl);
		// The API returns JSON, and json_decode produces an interesting mix of objects and arrays.
		$crimeDataCache[$lat.$lng] = json_decode($data);
	}
	
	$rate = 0;
	if($crimeType=="all-crime")return count($crimeDataCache[$lat.$lng]);
	$crimeSet=$crimeDataCache[$lat.$lng];
	foreach ($crimeSet as $crime){
		if($crime->category==$crimeType)$rate++;
	}
	
	return $rate;
}

// Here are the plugins themselves
// All-encompassing crime rate
class crime_all {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "all-crime";
	
	// The human-readable name
	public $hrname = "Overall crime rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "all-crime");
		
		return $rate;
		}
		
	}
	
// ASB
class crime_asb {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "asb";
	
	// The human-readable name
	public $hrname = "Antisocial behaviour rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "anti-social-behaviour");
		
		return $rate;
		}
		
	}
	
// Drugs
class crime_drugs {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "drugs";
	
	// The human-readable name
	public $hrname = "Drugs-related crime rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "drugs");
		
		return $rate;
		}
		
	}
	
// Criminal damage and arson
class crime_cda {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "cda";
	
	// The human-readable name
	public $hrname = "Criminal damage and arson rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "criminal-damage-arson");
		
		return $rate;
		}
		
	}
	
// Burglary
class crime_burglary {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "burglary";
	
	// The human-readable name
	public $hrname = "Burglary rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "burglary");
		
		return $rate;
		}
		
	}

// Violent crime
class crime_violent {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "violent-crime";
	
	// The human-readable name
	public $hrname = "Violent crime rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "violent-crime");
		
		return $rate;
		}
		
	}

// Violent crime
class crime_weapons {
	// The category identifier
	public $category = "crime";
	
	// The name identifier
	public $name = "public-disorder-weapons";
	
	// The human-readable name
	public $hrname = "Armed public disorder rate";
	
	// Determines which result wins.
	public $better = LOWER_IS_BETTER;
	
	// The units that the results are returned in.
	public $units = "";
		
	// Whether the results from this are allowed to be cached.
	public $can_cache = TRUE;
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($lat, $lng, "public-disorder-weapons");
		
		return $rate;
		}
		
    }

	// Add the plugins - hey look, this is a comment
	$plugins[] = new crime_all();
	$plugins[] = new crime_asb();
	$plugins[] = new crime_drugs();
	$plugins[] = new crime_cda();
	$plugins[] = new crime_burglary();
	$plugins[] = new crime_violent();
	$plugins[] = new crime_weapons();
?>
