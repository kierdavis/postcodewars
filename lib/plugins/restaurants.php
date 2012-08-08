<?php
	require_once "../priximity.php";
    // Copy this file into lib/plugins/ and name it appropriately. Then follow the comments in this
    // file to fill in the gaps.

    // Also, don't forget to check the $category_names variable in search.php - try to use one of
    // the categories there, but you can define your own if you need to.
    
    // Change this name
    class Restaurants {
        // The category identifier - should be lowercase and hyphen-separated e.g. "crime"
        public $category = "amenities";
        
        // The name identifier - should be lowercase and hyphen-separated e.g. "school-proximity"
        public $name = "restaurant";
        
        // The human-readable name - this will be displayed in the results table e.g. "School proximity"
        public $hrname = "Restaurants near-by";
        
        // The units that the results are returned in.
        public $units = "";
        
        // Should be either LOWER_IS_BETTER or HIGHER_IS_BETTER - determines which result wins.
        public $better = HIGHER_IS_BETTER;
        
        // The get_result method should perform the searches and return the two results.
        // $db is a mysqli object connected to the database.
        // $location is an associative array which contain the following entries:
        //     "postcode" => the postcode
        //     "lat" => the latitude
        //     "lng" => the longitude
        public function get_result($db, $loc) {
            // Do something with $location
            $result=get_all_results($loc["postcode"],"restaurant","restaurant",$loc["lat"],$loc["lng"],20000);
            // Should return a number - this is the result that is displayed.
            $no_of_restaurants=count($result);
            return $no_of_restaurants;
        }
    }
    
    // Update the name of the class here too.
    // This inserts the plugin into the plugin index.
    $plugins["restaurants"] = new Restaurants();
?>
