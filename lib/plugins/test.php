<?php
    // TestPlugin exists merely to ensure that the data flows through the code properly
    class TestPlugin {
        // The category identifier - should be lowercase and hyphen-separated e.g. "crime"
        public $category = "test-category";
        
        // The name identifier - should be lowercase and hyphen-separated e.g. "school-proximity"
        public $name = "test";
        
        // The human-readable name - this will be displayed in the results table e.g. "School proximity"
        public $hrname = "Test";
        
        // Should be either LOWER_IS_BETTER or HIGHER_IS_BETTER - determines which result wins.
        public $better = LOWER_IS_BETTER;
        
        // The get_result method should perform the searches and return the two results.
        // $db is a mysqli object connected to the database.
        // $location is an associative array which contain the following entries:
        //     "postcode" => the postcode
        //     "lat" => the latitude
        //     "lng" => the longitude
        public function get_result($db, $location) {
            // Do something with $location
            
            // Should return a number - this is the result that is displayed.
            return $location["lat"];
        }
    }
    
    // Update the name of the class here too.
    // This inserts the plugin into the plugin index.
    $plugins[] = new TestPlugin();
?>
