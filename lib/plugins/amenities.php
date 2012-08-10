<?php
	require_once "../lib/proximity.php";
	
	class ArtGalleries {
		public $category = "amenities";			// Category identifier - lowercase and hyphen-separated
		public $name = "art-galleries-nearby";	// Name identifier - ""
<<<<<<< HEAD
<<<<<<< HEAD
		public $hrname = "Art galleries";		// Displayed in results table
		public $units = "w/in 2km";				// Units of the results
=======
		public $hrname = "Art galleries (within 2km)";		// Displayed in results table
		public $units = "";				// Units of the results
>>>>>>> fe81428a31be2980a1461305acdf296d0424242b
=======
		public $hrname = "Art galleries";		// Displayed in results table
		public $units = "w/in 2km";				// Units of the results
>>>>>>> c0bc63283f57bbfc65f9b5187dd598db49b53c5c
		public $better = HIGHER_IS_BETTER;		// LOWER_IS_BETTER or HIGHER_IS_BETTER - determines winner
		public $can_cache = FALSE;					// If results are cached or not
		
		// The get_result method should perform the searches and return the two results.
		// $db is a mysqli object connected to the database.
		// $location is an associative array which contains: "postcode", "lat", "lng"
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "art_gallery", $loc["lat"], $loc["lng"], 2000); // Returns number
			$no_of_art_galleries = count($result);
			return $no_of_art_galleries;
		}
	}
	
	// Bars
	
	class Bars {
		public $category = "amenities";
		public $name = "bars-nearby";
<<<<<<< HEAD
<<<<<<< HEAD
		public $hrname = "Bars";
		public $units = "w/in 500m";
=======
		public $hrname = "Bars (within 500m)";
		public $units = "";
>>>>>>> fe81428a31be2980a1461305acdf296d0424242b
=======
		public $hrname = "Bars";
		public $units = "w/in 500m";
>>>>>>> c0bc63283f57bbfc65f9b5187dd598db49b53c5c
		public $better = HIGHER_IS_BETTER;
		public $can_cache = FALSE;
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "bar", $loc["lat"], $loc["lng"], 500);
			$no_of_bars = count($result);
			return $no_of_bars;
		}
	}
	
	// Museums
	
	class Museums {
		public $category = "amenities";
		public $name = "museums-nearby";
<<<<<<< HEAD
<<<<<<< HEAD
		public $hrname = "Museums";
		public $units = "w/in 2km";
=======
		public $hrname = "Museums (within 2km)";
		public $units = "";
>>>>>>> fe81428a31be2980a1461305acdf296d0424242b
=======
		public $hrname = "Museums";
		public $units = "w/in 2km";
>>>>>>> c0bc63283f57bbfc65f9b5187dd598db49b53c5c
		public $better = HIGHER_IS_BETTER;
		public $can_cache = FALSE;
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "museum", $loc["lat"], $loc["lng"], 2000);
			$no_of_museums = count($result);
			return $no_of_museums;
		}
	}
	
	// Restaurants
	
	class Restaurants {
		public $category = "amenities";
		public $name = "restaurants-nearby";
<<<<<<< HEAD
<<<<<<< HEAD
		public $hrname = "Restaurants";
		public $units = "w/in 500m";
=======
		public $hrname = "Restaurants (within 500m)";
		public $units = "";
>>>>>>> fe81428a31be2980a1461305acdf296d0424242b
=======
		public $hrname = "Restaurants";
		public $units = "w/in 500m";
>>>>>>> c0bc63283f57bbfc65f9b5187dd598db49b53c5c
		public $better = HIGHER_IS_BETTER;
		public $can_cache = FALSE;
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"], "restaurant", $loc["lat"], $loc["lng"], 500);
			$no_of_restaurants = count($result);
			return $no_of_restaurants;
		}
	}
	
	$plugins["art-galleries"] = new ArtGalleries(); // Inserts the plugin into the plugin index
	$plugins["bars"] = new Bars();
	$plugins["museums"] = new Museums();
	$plugins["restaurants"] = new Restaurants();
?>
