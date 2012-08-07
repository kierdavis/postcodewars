<?php
    require_once "search-schools.php";
    require_once "proximity.php";
    function search($postcode) {
        // Remove spaces
        $postcode = str_replace(" ", "", $postcode);
       
        // Connect to the DB
        $db = new mysqli("localhost", "yrs2012app-user", "vOdQ04wDTtIS3GeylBER1nNrAo76ZLFJU9hzuxsKmCPi8WcHqbYfVpjXkMag");
        
        // Calculate latitude & longitude
        $latlng_result = postcode2latlng($db, $postcode);
        $lat = $latlng_result["lat"];
        $lng = $latlng_result["lng"];
        
        // Perform actual result functions
        $nearest_school = search_schools($db, $postcode, $lat, $lng);
        get_nearest_result($postcode,"a%26e");
        get_ae_proximity($db, $postcode, $lat, $lng);
        
        // Build the response
        $result = array(
            "overall_score" => 0.0,
            
            "results_living" => array(
                
            ),
            
            "results_staying" => array(
                "nearest_school" => $nearest_school,
            ),
        );
        
        return $result;
    }
?>
