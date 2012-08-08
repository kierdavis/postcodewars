<?php
    require_once "../lib/include.php";
    require_once "../lib/util.php";
    
    require_once "../lib/search.php";
    
    if (!array_key_exists("postcode1", $_GET) || !array_key_exists("postcode2", $_GET)) {
        redirect("index.php");
        exit;
    }
    
    $postcode1 = $_GET["postcode1"];
    $postcode2 = $_GET["postcode2"];
    $result = search($postcode1, $postcode2);
?>

<html>
    <head>
        <title>Results for <?php echo htmlentities($postcode1); ?> vs <?php echo htmlentities($postcode2); ?></title>
    </head>
    
    <body>
        <h1><?php echo htmlentities($postcode1); ?> vs <?php echo htmlentities($postcode2); ?></h1>
        
<?php
    foreach ($result as $categoryID => $category) {
?>

        <h3><?php echo htmlentities($category["name"]) ?></h3>
        <table>
            <tbody>                

<?php
        foreach ($category as $itemID => $item) {
            if ($itemID != "name") {
?>

                <tr>
                    <td><?php echo htmlentities($item["result1"]); ?></td>
                    <th><?php echo htmlentities($item["name"]); ?></th>
                    <td><?php echo htmlentities($item["result2"]); ?></td>
                </tr>

<?php
            }
        }
?>

            </tbody>
        </table>

<?php
    }
?>
    </body>
</html>
