<?php
    require_once "include.php";
    require_once "util.php";
    
    $plugins = array();
    
    // Load all plugins
    foreach (glob("../lib/plugins/*.php") as $filename) {
        include $filename;
    }
    
    $category_names = array(
        "test-category" => "Test",
        "care" => "Care",
    );
    
    function search($postcode1, $postcode2) {
        global $plugins, $category_names;
        
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
            
            if (!array_key_exists($category, $result)) {
                $result[$category] = array(
                    "name" => $category_names[$category],
                );
            }
            
            $r1 = $plugin->get_result($db, $location1);
            $r2 = $plugin->get_result($db, $location2);
            $result[$category][$name] = array(
                "name" => $hrname,
                "higher_is_better" => $better,
                "result1" => $r1,
                "result2" => $r2,
            );
        }
		
		//we should do some sort of caching so that when
        return $result;
    }
?>
