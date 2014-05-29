<?php
	require_once "../lib/distance.php"; // uses different file
	
	class Hospitals {
		public $category = "proximities";			// Category identifier - lowercase and hyphen-separated
		public $name = "hospital-distance";		// Name identifier - ""
		public $hrname = "Nearest hospital";	// Displayed in results table
		public $units = "km";						// Units of the results
		public $better = LOWER_IS_BETTER;		// LOWER_IS_BETTER or HIGHER_IS_BETTER - determines winner
		public $can_cache = TRUE;					// If results are cached or not
		
		// The get_result method should perform the searches and return the two results.
		// $db is a mysqli object connected to the database.
		// $location is an associative array which contains: "postcode", "lat", "lng"
		public function get_result($db, $loc) {
			return dist_to_result($loc["postcode"],"hospital","hospital|infirmary",$loc["lat"],$loc["lng"]);
		}
	}
	
	// Train stations
	
	class TrainStations {
		public $category = "proximities";
		public $name = "train-station-distance";
		public $hrname = "Nearest train station";
		public $units = "km";
		public $better = LOWER_IS_BETTER;
		public $can_cache = TRUE;
		
		public function get_result($db, $loc) {
			return dist_to_result($loc["postcode"],"train_station","",$loc["lat"],$loc["lng"]);
		}
	}
	
	$plugins["hospital-distance"] = new Hospitals(); // Inserts the plugin into the plugin index
	$plugins["train-station-distance"] = new TrainStations();
?>
