<?php
    require_once "../lib/include.php";
    
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWD, MSQL_DB);
    $res = $db->query("DELETE FROM cache");
    if ($res === FALSE) {
        echo "MySQL Error: " . $db->error . "\n";
    }
    else {
        echo "Cache cleared.\n";
    }
?>
