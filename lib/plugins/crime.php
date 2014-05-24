<?php
require_once "../lib/include.php";

global $crimeDataCache,$forceInfoCache,$NhoodPolyCache,$ForceNhoodCache;
$crimeDataCache=[];
$NhoodInfoCache=[];
$NhoodPolyCache=[];
$ForceNhoodCache=[];

// Function to get force id and neighbourhood code from lat and long 
function getForceAndNhood($lat, $lng) {
	global $ForceNhoodCache;
	if(array_key_exists($lat.$lng,$ForceNhoodCache)==TRUE)return $ForceNhoodCache[$lat.$lng];
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
	$ForceNhoodCache[$lat.$lng] = json_decode($data);
	return $ForceNhoodCache[$lat.$lng];
}

//returns the area of a polygon in sqkm
function areaOfPoly($points,$centerLat=null,$centerLng=null){
	$coordinatePoints=[];
	if($centerLat==null)$centerLat=$points[0]->latitude;
	if($centerLng==null)$centerLng=$points[0]->longitude;
	foreach($points as $point){
		$coordinatePoints[]=[(($point->latitude-$centerLat)*20004/180),
							 (($point->longitude-$centerLng)*40075.16/360)];
	}
	$area=0;
	//loop through crossing coordinates
	for($i=0;$i+1<count($coordinatePoints);$i++){
		$area+=$coordinatePoints[$i][0]*$coordinatePoints[$i+1][1];
		$area-=$coordinatePoints[$i][1]*$coordinatePoints[$i+1][0];
	}
	//special case
	$area+=$coordinatePoints[$i][0]*$coordinatePoints[0][1];
	$area-=$coordinatePoints[$i][1]*$coordinatePoints[0][0];
	
	//final result
	$area=abs($area/2);
	return $area;
}

// Function to retrieve the bounding area of a neighbourhood
function getNhoodPoly($force, $nhood) {
	global $NhoodPolyCache;
	if(array_key_exists($force.$nhood,$NhoodPolyCache)==TRUE)return $NhoodPolyCache[$force.$nhood];
	
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
	
	$NhoodPolyCache[$force.$nhood]=json_decode($data);
	
	return $NhoodPolyCache[$force.$nhood];
}
	
function getNhoodInfo($force,$nhood){
	global $NhoodInfoCache;
	if(array_key_exists($force.$nhood,$NhoodInfoCache)==TRUE)return $NhoodInfoCache[$force.$nhood];
	
	$userpass = POLICE_API_KEY;
	$url = "http://policeapi2.rkh.co.uk/api/$force/$nhood";

	$curl = curl_init();

	// Gotta put dat password in.
	curl_setopt($curl, CURLOPT_USERPWD, $userpass);
	curl_setopt($curl, CURLOPT_URL, $url);
	
	// Without this, we just get "1" or similar.
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

	$data = curl_exec($curl);

	curl_close($curl);
	
	$NhoodInfoCache[$force.$nhood]=json_decode($data);
	
	return $NhoodInfoCache[$force.$nhood];
}
function getCrimeData($lat, $lng){
	global $crimeDataCache;
	
	if(array_key_exists($lat.$lng,$crimeDataCache)==TRUE)return $crimeDataCache[$lat.$lng];
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
	return $crimeDataCache[$lat.$lng];
}


function getCrimeRate($force, $nhood, $lat, $lng, $crimeType) {
	$crimeResultData=getCrimeData($lat, $lng);
	$rate = 0;
	if($crimeType=="all-crime"){
		$rate=count($crimeResultData);
	}
	else{
		$crimeSet=$crimeResultData;
		foreach ($crimeSet as $crime){
			if($crime->category==$crimeType)$rate++;
		}
	}
	//$rate is for 1mile radius
	
	$NhoodResultInfo=getNhoodInfo($force,$nhood);
	$population=$NhoodResultInfo->population;
	$NhoodPolyInfo=getNhoodPoly($force, $nhood);
	$area=areaOfPoly($NhoodPolyInfo,$lat,$lng);
	
	$populationDensity=$population/$area;//in sq km
	$crimePer1000=($rate/(5.056*$populationDensity))*1000;
	return round($crimePer1000,1);
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "all-crime");
		
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "anti-social-behaviour");
		
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "drugs");
		
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "criminal-damage-arson");
		
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "burglary");
		
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "violent-crime");
		
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
		$rate = getCrimeRate($force, $neighbourhood, $lat, $lng, "public-disorder-weapons");
		
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
