<?php
    function postcode2latlng($db, $postcode) {
        $postcode_encoded = $db->real_escape_string($postcode);
        
        $result = $db->query("SELECT lat, lng FROM postcodes WHERE postcode = \"$postcode_encoded\"");
        if ($result === FALSE) {
            die("MySQL Error: " . $db->error);
        }
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return array("lat" => $row[0], "lng" => $row[1]);
        }
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, "http://www.uk-postcodes.com/postcode/" . $postcode . ".json");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        
        $d = json_decode($data, true);
        $lat = (float) $d["geo"]["lat"];
        $lng = (float) $d["geo"]["lng"];
        
        $lat_encoded = $db->real_escape_string($lat);
        $lng_encoded = $db->real_escape_string($lng);
        
        $result = $db->query("INSERT INTO postcodes VALUES (\"$postcode_encoded\", \"$lat_encoded\", \"$lng_encoded\")");
        if ($result === FALSE) {
            die("MySQL Error: " . $db->error);
        }
        
        return array("lat" => $lat, "lng" => $lng);
    }
?>
