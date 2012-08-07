<?php
    require_once "search-schools.php"
    
    function search($postcode) {
        $db = new mysqli("localhost", "yrs2012app-user", "vOdQ04wDTtIS3GeylBER1nNrAo76ZLFJU9hzuxsKmCPi8WcHqbYfVpjXkMag");
        
        $distance_to_nearest_school = search_schools($db, $postcode);
        
        $result = array(
            "overall_score" => 0.0,
            
            "results_living" => array(
                
            ),
            
            "results_staying" => array(
                "distance_to_nearest_school" => $distance_to_nearest_school,
            ),
        );
        
        return $result;
    }
?>
