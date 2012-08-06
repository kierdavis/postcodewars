<?php
    //require_once "../lib/something.php"
    
    if (!array_key_exists("postcode", $_GET)) {
        redirect("index.php");
        exit;
    }
    
    $postcode = $_GET["postcode"];
    
    // Do something to transform $postcode into $result
    // $result should be an array of some sort that is encoded into JSON and passed back to the
    // search page.
    
    /*
    $result = array(
        "overall_score" => average($scores),
        "results_staying" => $results_staying,
        "results_living" => $results_living,
    )
    */
    
    $result = array("herp", "derp", "burp");
    
    echo json_encode($result);
?>
