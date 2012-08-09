<?php
	require_once "../lib/proximity.php";
	
	class ArtGalleries {
		public $category = "amenities"; // Category identifier - lowercase and hyphen-separated
		
		public $name = "art-galleries"; // Name identifier - ""
		
		public $hrname = "Art galleries near-by"; // Displayed in results table
		
		public $units = ""; // Units of the results
		
		public $better = HIGHER_IS_BETTER; // LOWER_IS_BETTER or HIGHER_IS_BETTER - determines winner
		
		public $can_cache = FALSE; // If results are cached or not
		
		// The get_result method should perform the searches and return the two results.
		// $db is a mysqli object connected to the database.
		// $location is an associative array which contains: "postcode", "lat", "lng"
		
		public function get_result($db, $loc) {
			$result = get_all_results($loc["postcode"],"","art_gallery",$loc["lat"],$loc["lng"],5000); // Returns number
			$no_of_artgalleries = count($result);
			return $no_of_artgalleries;
		}
	}
	
	$plugins["art-galleries"] = new ArtGalleries(); // Inserts the plugin into the plugin index
?>