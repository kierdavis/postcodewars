<?php
    if (!array_key_exists("postcode", $_GET)) {
        redirect("index.php");
        exit;
    }
    
    $postcode = $_GET["postcode"];
    
    // Do something to transform $postcode into $result
    // $result should be an array of some sort that is encoded into JSON and passed back to the
    // search page.
    
    $result = array("herp", "derp", "burp");
    
    echo json_encode($result);
?>
