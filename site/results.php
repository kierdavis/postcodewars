<?php
    require_once "../lib/include.php";
    require_once "../lib/search.php";
    
    if (!array_key_exists("postcode1", $_GET) || !array_key_exists("postcode2", $_GET)) {
        redirect("index.php");
        exit;
    }
    
    $postcode1 = $_GET["postcode1"];
    $postcode2 = $_GET["postcode2"];
    $result = search($postcode1, $postcode2);
    
    echo json_encode($result);
?>
