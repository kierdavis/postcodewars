<?php
    if (!array_key_exists("postcode" $_GET)) {
        redirect("index.php");
        exit;
    }
    
    $postcode = $_GET["postcode"];
    
    // Do something to transform $postcode into $score, $results_staying and $results_living
    // $score is the overall score e.g. 80
    // $results_staying and $results_living are arrays mapping names of measurements to their score
    // e.g. "Traffic" -> 96
?>

<html>
    <head>
        <title><?php echo $postcode ?> - Results</title>
        <link rel="stylesheet" type="text/css" href="/static/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="/static/css/results.css"/>
    </head>
    
    <body>
        
    </body>
</html>
