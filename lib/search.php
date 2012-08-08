<?php
    require_once "include.php"
    
    $plugins = array();
    
    // Load all plugins
    foreach (glob("plugins/*.php") as $filename) {
        include $filename;
    }
    
    function search($postcode1, $postcode2) {
        // Remove spaces
        $postcode1 = str_replace(" ", "", $postcode1);
        $postcode2 = str_replace(" ", "", $postcode2);
       
        // Connect to the DB
        $db = new mysqli("localhost", "yrs2012app-user", "vOdQ04wDTtIS3GeylBER1nNrAo76ZLFJU9hzuxsKmCPi8WcHqbYfVpjXkMag", "yrs2012app");
        
        // Calculate latitude & longitude
        $location1 = postcode2location($db, $postcode1);
        $location2 = postcode2location($db, $postcode2);
        
        $result = array();
        
        foreach ($plugins as $plugin) {
            $category = $plugin->category;
            $name = $plugin->name;
            $hrname = $plugin->hrname;
            $better = $plugin->better;
            
            $r = $plugin->run($db, $location1, $location2);
            $result[$category][$name] = array(
                "hrname" => $hrname,
                "result1" => $r["result1"],
                "result2" => $r["result2"],
            );
        }
		
		
		//we should do some sort of caching so that when
        return json_encode($result);
    }
?>
