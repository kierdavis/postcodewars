<?php
    require_once "include.php";
    require_once "util.php";
    
    $plugins = array();
    $plugin_log = fopen("plugin.log", "a");
    
    function logmsg($plugin_name, $msg) {
        global $plugin_log;
        
        fwrite($plugin_log, "Message from plugin '" . $plugin_name . "': " . $msg . "\n");
    }
    
    // Load all plugins
    foreach (glob("../lib/plugins/*.php") as $filename) {
        include $filename;
    }
    
    $category_names = array(
        "test-category" => "Test",
        "care" => "Care",
    );
    
    function search($postcode1, $postcode2) {
        global $plugins, $category_names, $plugin_log;
        
        // Remove spaces
        $postcode1 = str_replace(" ", "", $postcode1);
        $postcode2 = str_replace(" ", "", $postcode2);
       
        // Connect to the DB
        $db = new mysqli("localhost", "yrs2012app-user", "vOdQ04wDTtIS3GeylBER1nNrAo76ZLFJU9hzuxsKmCPi8WcHqbYfVpjXkMag", "yrs2012app");
        
        // Calculate latitude & longitude
        $location1 = postcode2location($db, $postcode1);
        $location2 = postcode2location($db, $postcode2);
        
        $breakdown = array();
        
        foreach ($plugins as $plugin) {
            $category = $plugin->category;
            $name = $plugin->name;
            $hrname = $plugin->hrname;
            $units = $plugin->units;
            $better = $plugin->better;
            
            if (!array_key_exists($category, $breakdown)) {
                $breakdown[$category] = array(
                    "_name" => $category_names[$category],
                    "_score1" => 0,
                    "_score2" => 0,
                );
            }
            
            $r1 = 0;
            $r2 = 0;
            
            try {
                $r1 = $plugin->get_result($db, $location1);
                $r2 = $plugin->get_result($db, $location2);
            }
            
            catch (Exception $e) {
                fwrite($plugin_log, "Error from plugin '" . $name . "': " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
                continue;
            }
            
            $winner1 = false;
            
            if ($better == LOWER_IS_BETTER) {
                $winner1 = $r1 < $r2;
            }
            else {
                $winner1 = $r1 > $r2;
            }
            
            if ($winner1) {
                $breakdown[$category]["_score1"]++;
            }
            else {
                $breakdown[$category]["_score2"]++;
            }
            
            $breakdown[$category][$name] = array(
                "name" => $hrname,
                "units" => $units,
                "result1" => $r1,
                "result2" => $r2,
                "winner1" => $winner1,
            );
        }
        
        return $breakdown;
    }
?>
