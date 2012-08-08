	<?php
	function get_all_school_results($postcode,$lat,$lng){
	    $c = curl_init();
		$url="https://maps.googleapis.com/maps/api/place/textsearch/json";
		$argstr="?query=primary+schooltypes=school&sensor=false&key=".GOOGLE_API_KEY;
		$argstr.="&location=".$lat.",".$lng;
		if($rankbydist){
			$argstr.="&rankby=distance";
		}
		else{
			$argstr.="&radius=".$radius;
		}
		logmsg("proximity-lib",$url.$argstr);
        curl_setopt($c, CURLOPT_URL, $url . $argstr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        $d = json_decode($data);
		
        //var_dump($d);
        return $d->results;
	}
	function get_nearest_result($postcode,$criteria,$type,$lat,$lng,$rankbydist){
        $d=get_all_results($postcode,$criteria,$type,$lat,$lng,"30000",$rankbydist);
        if (count($d) < 1) {
            return FALSE;
        }
        
		//gets the lat and long of the first result
		$endloclat=$d[0]->geometry->location->lat;
		$endloclng=$d[0]->geometry->location->lng;
		return array("geo"=>array($endloclat,$endloclng),"name"=>$d[0]->name,"data"=>json_encode($d));
	}
	require_once "../lib/proximity.php";
    // Copy this file into lib/plugins/ and name it appropriately. Then follow the comments in this
    // file to fill in the gaps.

    // Also, don't forget to check the $category_names variable in search.php - try to use one of
    // the categories there, but you can define your own if you need to.
    
    // Change this name
    class SchoolPlugin {
        // The category identifier - should be lowercase and hyphen-separated e.g. "crime"
        public $category = "schools";
        
        // The name identifier - should be lowercase and hyphen-separated e.g. "school-proximity"
        public $name = "school-results";
        
        // The human-readable name - this will be displayed in the results table e.g. "School proximity"
        public $hrname = "School Ofsted Results";
        
        // The units that the results are returned in.
        public $units = "";
        
        // Should be either LOWER_IS_BETTER or HIGHER_IS_BETTER - determines which result wins.
        public $better = HIGHER_IS_BETTER;
        
        // Whether the results from this are allowed to be cached.
        public $can_cache = TRUE;
        
        // The get_result method should perform the searches and return the two results.
        // $db is a mysqli object connected to the database.
        // $location is an associative array which contain the following entries:
        //     "postcode" => the postcode
        //     "lat" => the latitude
        //     "lng" => the longitude
        public function get_result($db, $loc) {
        	
        	//find the nearest school!
			logmsg("school-results", "first line of school results");
        	$nearest_school=get_nearest_result($loc["postcode"],"","school",$loc["lat"],$loc["lng"]);
			logmsg("school-results", "json_data".json_encode($nearest_school));
        	
			$addr_of_school=$nearest_school->formatted_address;
			$matches=array();
			logmsg("school-results", $addr_of_school);
			preg_match("[a-zA-Z]{2}[1-9]{1,2}[a-zA-Z]?\s?[1-9]{1}[a-zA-Z]{2}", $addr_of_school, $matches);
			logmsg("school-results", json_encode($matches));
			if(!array_key_exists(0, $matches)){
				return false;
			};
        	$postcode_of_school=$matches[0];
            // Do something with $location
			//TO DO : We need to confirm that the MySQL column names and table name is correct if not then change it!
            $postcode_encoded = $db->real_escape_string($postcode_of_school);
			$queryToSend = "SELECT testscore FROM schools WHERE postcode = \"$postcode_encoded\"";
			$res = $db->query($queryToSend);
			logmsg("school-results", "got to line 51");
            
            if ($res->num_rows == 0) {
                return false;
            }
            
            $score = 0.0;
			logmsg("school-results", "got to line 57");
			for ($i = 0; $i < $res->num_rows; $i++) {
				$row = $res->fetch_assoc();
				$score += $row["testscore"];
			}
            
			return $score / $res->num_rows;
        }
    }
    
    // Update the name of the class here too.
    // This inserts the plugin into the plugin index.
    $plugins["schools"] = new SchoolPlugin();
?>
