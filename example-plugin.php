<?php
    // Copy this file into lib/plugins/ and name it appropriately. Then follow the comments in this
    // file to fill in the gaps.
    
    // Change this name
    class MyPlugin {
        // The category identifier - should be lowercase and hyphen-separated e.g. "crime"
        public $category = "";
        
        // The name identifier - should be lowercase and hyphen-separated e.g. "school-proximity"
        public $name = "";
        
        // The human-readable name - this will be displayed in the results table e.g. "School proximity"
        public $hrname = "";
        
        // Should be either LOWER_IS_BETTER or HIGHER_IS_BETTER - determines which result wins.
        public $better = LOWER_IS_BETTER;
        
        // The run method should perform the searches and return the two results.
        // $db is a mysqli object connected to the database.
        // $location1 and $location2 are associative arrays which contain the following entries:
        //     "postcode" => the postcode
        //     "lat" => the latitude
        //     "lng" => the longitude
        public function run($db, $location1, $location2) {
            // Do something with $location1 & $location2
            
            // Put the results (the numbers) into $result1 and $result2
            
            return array("result1" => $result1, "result2" => $result2);
        }
    }
    
    // Update the name of the class here too.
    // This inserts the plugin into the plugin index.
    $plugins[] = new MyPlugin();
?>
