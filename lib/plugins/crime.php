<?php
// Function to get force id and neighbourhood code from lat and long
function getForceAndNhood($lat, $lng) {
	// My unique username and password, woo! The API requires this on every query.
	$userpass = "wejoc29:fc5eeb9a6565be057ee334f7e1665dfb";
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
	
function getCrimeRate($force, $nhood, $crimeType) {
	// My unique username and password, woo! The API requires this on every query.
	$userpass = "wejoc29:fc5eeb9a6565be057ee334f7e1665dfb";
	$url = "http://policeapi2.rkh.co.uk/api/$force/$nhood/crime";

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
	
	// Getting YYYY-MM of this time two months ago (that's the latest police data).
	$date = new DateTime();
	
	$date->sub(new DateInterval("P2M"));
	
	$dateFormatted = $date->format("Y-m");
	
	$rate = $dataObj->crimes->$dateFormatted->$crimeType->crime_rate;
	
	if (!is_null($rate)) {
		return $rate;
		}
	else return NULL;
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
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($force, $neighbourhood, "all-crime");
		
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
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($force, $neighbourhood, "anti-social-behaviour");
		
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
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($force, $neighbourhood, "drugs");
		
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
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($force, $neighbourhood, "criminal-damage-arson");
		
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
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($force, $neighbourhood, "burglary");
		
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
	
	public function get_result($db, $location) {
		// Put latitude and longitude into nice, easy variables.
		$lat = $location['lat'];
		$lng = $location['lng'];
		
		// Get the API-format force and neighbourhood IDs.
		$forceAndNhoodObj = getForceAndNhood($lat, $lng);
		$force = $forceAndNhoodObj->force;
		$neighbourhood = $forceAndNhoodObj->neighbourhood;
		
		// And the rate itself.
		$rate = getCrimeRate($force, $neighbourhood, "violent-crime");
		
		return $rate;
		}
		
    }

	// Add the plugins
	$plugins[] = new crime_all();
	$plugins[] = new crime_asb();
	$plugins[] = new crime_drugs();
	$plugins[] = new crime_cda();
	$plugins[] = new crime_burglary();
	$plugins[] = new crime_violent();
?>