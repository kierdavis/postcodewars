<?php
    require_once "../lib/search.php";
    
    if (!array_key_exists("postcode", $_GET)) {
        redirect("index.php");
        exit;
    }
    
    $postcode = $_GET["postcode"];
    $result = search($postcode);
    
    echo json_encode($result);
?>
