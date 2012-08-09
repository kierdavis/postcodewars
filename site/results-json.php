<?php
    require_once "../lib/include.php";
    require_once "../lib/util.php";
    
    require_once "../lib/search.php";
    
    $postcode1 = "";
    $postcode2 = "";
    $result = null;
    
    if (array_key_exists("postcode1", $_GET) && array_key_exists("postcode2", $_GET)) {
        $postcode1 = $_GET["postcode1"];
        $postcode2 = $_GET["postcode2"];
        
        if ($postcode1 !== "" && $postcode2 !== "") {
            $result = search($postcode1, $postcode2);
        }
    }
    
    if ($result === null) {
        header("HTTP/1.1 400 Bad Request");
    }
    else {
        echo json_encode($result);
    }
?>
