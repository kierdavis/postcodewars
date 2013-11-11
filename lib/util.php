<?php
    function postcode2location($postcode) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, "http://www.uk-postcodes.com/postcode/" . $postcode . ".json");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($c);
        curl_close($c);
        
        $d = json_decode($data, true);
        $lat = (float) $d["geo"]["lat"];
        $lng = (float) $d["geo"]["lng"];
        $town = $d["administrative"]["ward"]["title"];
        return array("lat" => $lat, "lng" => $lng, "town" => $town, "postcode" => $postcode);
    }
?>
