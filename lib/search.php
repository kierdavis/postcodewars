<?php
    require_once "include.php";
    require_once "util.php";
    
    $plugins = array();
    $plugin_log = fopen("plugin.log", "a");
    if ($plugin_log === FALSE) {
    	$plugin_log_add=fopen("plugin.log", "w");
		if($plugin_log_add == FALSE){
			die("Couldn't create plugin.log");
		}
        die("Could not open plugin.log for appending");
    }
    
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
        "amenities" => "Amenities",
        "schools" => "Schools",
        "transport" => "Transport",
        "crime" => "Crime (offences per 1000 people)",
		"house-price" => "House Prices"

    );
    
    function load_from_cache($db, $plugin, $location) {
        global $plugin_log;
        
        $postcode_encoded = $db->real_escape_string($location["postcode"]);
        $plugin_encoded = $db->real_escape_string($plugin->name);
        $res = $db->query("SELECT id, value FROM cache WHERE postcode = '$postcode_encoded' AND plugin = '$plugin_encoded'");
        if ($res === FALSE) {
            fwrite($plugin_log, "MySQL error: " . $db->error . "\n");
            return FALSE;
        }
        
        if ($res->num_rows == 0) {
            return "NORESULT";
        }
        
        $row = $res->fetch_row();
        $id = $row[0];
        $v = $row[1];
        
        $res = $db->query("UPDATE cache SET last_accessed = UNIX_TIMESTAMP() WHERE id = $id");
        if ($res === FALSE) {
            fwrite($plugin_log, "MySQL error: " . $db->error . "\n");
            return FALSE;
        }
        return $v;
    }
    
    function store_to_cache($db, $plugin, $location, $result) {
        global $plugin_log;
        
        $postcode_encoded = $db->real_escape_string($location["postcode"]);
        $plugin_encoded = $db->real_escape_string($plugin->name);
        $result_encoded = $db->real_escape_string($result);
        $res = $db->query("INSERT INTO cache VALUES ('$plugin_encoded', '$postcode_encoded', '$result_encoded', UNIX_TIMESTAMP(), NULL)");
        if ($res === FALSE) {
            fwrite($plugin_log, "MySQL error: " . $db->error . "\n");
            return FALSE;
        }
    }
    
    function calc_result($db, $plugin, $location) {
        global $plugin_log;
        
        try {
            $res = $plugin->get_result($db, $location);
            
            if ($plugin->can_cache) {
                store_to_cache($db, $plugin, $location, $res);
            }
            
            return $res;
        }
        
        catch (Exception $e) {
            fwrite($plugin_log, "Error from plugin '" . $plugin->name . "': " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
            return FALSE;
        }
    }
    
    function get_result($db, $plugin, $location) {
        global $plugin_log;
        
        if ($plugin->can_cache) {
            $res = load_from_cache($db, $plugin, $location);
            if ($res === FALSE) {
                return FALSE;
            }
            
            if ($res !== "NORESULT") {
                return $res;
            }
        }
        
        return calc_result($db, $plugin, $location);
    }
    
    function search($postcode1, $postcode2) {
        global $plugins, $category_names, $plugin_log;
        
        // Sanitise
        $postcode1 = strtoupper(str_replace(" ", "", $postcode1));
        $postcode2 = strtoupper(str_replace(" ", "", $postcode2));
       
        // Connect to the DB
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWD, MYSQL_DB);
        
        // Calculate latitude & longitude
        $location1 = postcode2location($db, $postcode1);
        $location2 = postcode2location($db, $postcode2);
        
        $breakdown = array();
        
        $score1 = 0;
        $score2 = 0;
        
        foreach ($plugins as $plugin) {
            $category = $plugin->category;
            $name = $plugin->name;
            $hrname = $plugin->hrname;
            $units = $plugin->units;
            $better = $plugin->better;
            
            if (!array_key_exists($category, $breakdown)) {
                $breakdown[$category] = array(
                    "_name" => array_key_exists($category, $category_names) ? $category_names[$category] : $category,
                    "_score1" => 0,
                    "_score2" => 0,
                );
            }
            
            $r1 = get_result($db, $plugin, $location1);
            $r2 = get_result($db, $plugin, $location2);
            
            //echo $r1 . " " . $r2 . "\n";
            
            if ($r1 === FALSE || $r2 === FALSE) {
                continue;
            }
            
            $winner1 = false;
            $winner2 = false;
            
            if ($r1 != $r2) {
                if ($better == LOWER_IS_BETTER) {
                    $winner1 = $r1 < $r2;
                }
                else {
                    $winner1 = $r1 > $r2;
                }
                
                $winner2 = !$winner1;
            }
            
            else if (GET_POINT_ON_DRAW) {
                $winner1 = true;
                $winner2 = true;
            }
            
            if ($winner1) {
                $breakdown[$category]["_score1"]++;
                $score1++;
            }
            if ($winner2) {
                $breakdown[$category]["_score2"]++;
                $score2++;
            }
            
            $breakdown[$category][$name] = array(
                "name" => $hrname,
                "units" => $units,
                "result1" => $r1,
                "result2" => $r2,
                "winner1" => $winner1,
                "winner2" => $winner2,
            );
        }
        
        $breakdown["_score1"] = $score1;
        $breakdown["_score2"] = $score2;
        
        return $breakdown;
    }
?>
