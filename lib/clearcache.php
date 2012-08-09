<?php
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWD, MSQL_DB);
    $db->query("DELETE FROM cache");
?>
