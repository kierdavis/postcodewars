<?php
    // Copy this file into lib/plugins/ and name it appropriately. Then follow the comments in this
    // file to fill in the gaps.

    // Also, don't forget to check the $category_names variable in search.php - try to use one of
    // the categories there, but you can define your own if you need to.
    
    // Change this name
    class HousePrice {
        // The category identifier - should be lowercase and hyphen-separated e.g. "crime"
        public $category = "house-price";
        
        // The name identifier - should be lowercase and hyphen-separated e.g. "school-proximity"
        public $name = "price-rise-fall-percentage";
        
        // The human-readable name - this will be displayed in the results table e.g. "School proximity"
        public $hrname = "Average house price change over 12 months";
        
        // The units that the results are returned in.
        public $units = "%";
        
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
        //     "town" => the county-electoral area
        public function get_result($db, $location) {
			$townrefined = $location["town"];
			$housepriceunrefined = (file_get_contents("http://api.nestoria.co.uk/api?country=uk&pretty=1&action=metadata&place_name=" . $townrefined . "&encoding=xml"));
			
			
			
			$xmlfile2 = new SimpleXMLElement($housepriceunrefined);
			$oldhp = ($xmlfile2->xpath('/opt/response/metadata[@metadata_name="avg_4bed_property_buy_monthly"]/data[@name="2011_m2"]/@avg_price'));
			$newhp = ($xmlfile2->xpath('/opt/response/metadata[@metadata_name="avg_4bed_property_buy_monthly"]/data[@name="2012_m2"]/@avg_price'));
			
			
            
			if ($oldhp[0] >= $newhp[0]) {
			
				$result = (($newhp[0] - $oldhp[0]) / $oldhp[0]) * 100;
				//REFERENCE1) add code to instert a plus sign infront of $result here:
				
            }
			else {
				$result = (($oldhp[0] - $newhp[0]) / $newhp[0]) * 100;
				$result = -$result;
            }
            
            // Should return a number - this is the result that is displayed.
			//When echoed, $result will not display "+" sign when the the change in price is positive (see //REFERENCE1)

            $result = round($result, 2);
			return $result;

        }
    }
    
    // Update the name of the class here too.
    // This inserts the plugin into the plugin index.
    $plugins[] = new HousePrice();
?>
