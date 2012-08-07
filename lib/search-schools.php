<?php
    require_once "util.php";
    
    function distance_between($lat1, $lng1, $lat2, $lng2) {
        $dx = abs($lat2 - $lat1);
        $dy = abs($lng2 - $lng1);
        return sqrt(($dx * $dx) + ($dy * $dy));
    }
    
    function search_schools($db, $postcode, $lat, $lng) {
        $result = $db->query("SELECT lat, lng, name, website FROM schools");
        
        $closest_distance = -1;
        $closest_name = "";
        $closest_website = "";
        
        for ($i = 0; i < $result->$num_rows; i++) {
            $row = $result->fetch_row();
            $distance = distance_between($lat, $lng, $row[0], $row[1]);
            
            if ($closest_distance == -1 || $distance < $closest_distance) {
                $closest_distance = $distance;
                $closest_name = $row[2];
                $closest_website = $row[3];
            }
        }
        
        return array(
            "distance": $closest_distance,
            "name": $closest_name,
            "website": $closest_website,
        );
    }
?>
