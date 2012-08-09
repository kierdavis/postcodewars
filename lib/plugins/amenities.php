<?php
	require_once "../lib/proximity.php";
	
	class ArtGalleries {
		public $category = "amenities";						// Category identifier - lowercase and hyphen-separated
		public $name = "art-galleries-nearby";						// Name identifier - ""
		public $hrname = "Art galleries nearby";	// Displayed in results table
		public $units = "within 2km";							// Units of the results (/radius)
		public $better = HIGHER_IS_BETTER;				// LOWER_IS_BETTER or HIGHER_IS_BETTER - determines winner
		public $can_cache = FALSE;								// If results are cached or not
		
		// The get_result method should perform the searches and return the two results.
		// $db is a mysqli object connected to the database.
		// $location is an associative array which contains: "postcode", "lat", "lng"
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "", "art_gallery", $loc["lat"], $loc["lng"], 2000); // Returns number
			$no_of_art_galleries = count($result);
			return $no_of_art_galleries;
		}
	}
	
	// Bars
	
	class Bars {
		public $category = "amenities";
		public $name = "bars-nearby";
		public $hrname = "Bars nearby";
		public $units = "within 500m";
		public $better = HIGHER_IS_BETTER;
		public $can_cache = FALSE;
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "", "bar", $loc["lat"], $loc["lng"], 500);
			$no_of_bars = count($result);
			return $no_of_bars;
		}
	}
	
	// Museums
	
	class Museums {
		public $category = "amenities";
		public $name = "museums-nearby";
		public $hrname = "Museums nearby";
		public $units = "within 2km";
		public $better = HIGHER_IS_BETTER;
		public $can_cache = FALSE;
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "", "museum", $loc["lat"], $loc["lng"], 2000);
			$no_of_museums = count($result);
			return $no_of_museums;
		}
	}
	
	// Restaurants
	
	class Restaurants {
		public $category = "amenities";
		public $name = "restaurants-nearby";
		public $hrname = "Restaurants nearby";
		public $units = "within 500m";
		public $better = HIGHER_IS_BETTER;
		public $can_cache = FALSE;
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "", "restaurant", $loc["lat"], $loc["lng"], 500);
			$no_of_restaurants = count($result);
			return $no_of_restaurants;
		}
	}
	
	$plugins["art-galleries"] = new ArtGalleries(); // Inserts the plugin into the plugin index
	$plugins["bars"] = new Bars();
	$plugins["museums"] = new Museums();
	$plugins["restaurants"] = new Restaurants();
?>
