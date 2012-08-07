<?php
    function postcode2latlng($db, $postcode) {
        $result = $db->query("SELECT lat, lng FROM postcodes WHERE postcode = " . mysqli_real_escape_string($postcode));
        if ($result->$num_rows > 0) {
            $row = $result->fetch_row();
            return array("lat" => $row[0], "lng" => $row[1]);
        }
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, "http://www.uk-postcodes.com/postcode/" . $postcode . ".json");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        
        $r = json_decode($data);
        $r = $r["geo"];
        
        $db->query("INSERT INTO postcodes VALUES (%s, %s, %s)", $postcode, $r["lat"], $r["lng"]);
        
        return $r;
    }
?>
