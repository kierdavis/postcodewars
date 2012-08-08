<?php
    // Copy this file into lib/plugins/ and name it appropriately. Then follow the comments in this
    // file to fill in the gaps.

    // Also, don't forget to check the $category_names variable in search.php - try to use one of
    // the categories there, but you can define your own if you need to.
    
    // Change this name
    class SchoolPlugin {
        // The category identifier - should be lowercase and hyphen-separated e.g. "crime"
        public $category = "schools";
        
        // The name identifier - should be lowercase and hyphen-separated e.g. "school-proximity"
        public $name = "schools-results";
        
        // The human-readable name - this will be displayed in the results table e.g. "School proximity"
        public $hrname = "School Ofsted Results";
        
        // Should be either LOWER_IS_BETTER or HIGHER_IS_BETTER - determines which result wins.
        public $better = HIGHER_IS_BETTER;
        
        // The get_result method should perform the searches and return the two results.
        // $db is a mysqli object connected to the database.
        // $location is an associative array which contain the following entries:
        //     "postcode" => the postcode
        //     "lat" => the latitude
        //     "lng" => the longitude
        public function get_result($db, $location) {
            // Do something with $location
			//TO DO : We need to confirm that the MySQL column names and table name is correct if not then change it!
			$queryToSend = "SELECT ApsEngmatTest07 FROM SchoolData WHERE PostCode = ";
			$queryToSend .= $location;
			$res = $mysqli->query($queryToSend);

			for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) 
			{
				$res->data_seek($row_no);
				$row = $res->fetch_assoc();
				$score += $row;
			}
			
			//<!--Produce total score from database-->
			$result = $score / $res->num_rows;
            // Should return a number - this is the result that is displayed.
            return $result;
        }
    }
    
    // Update the name of the class here too.
    // This inserts the plugin into the plugin index.
    $plugins[] = new SchoolPlugin();
?>