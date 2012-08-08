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
?>

<!DOCTYPE html>

<html>
    <head>
        <title>PostCode Wars</title>

        <link href="/static/css/results.css" rel="stylesheet" type="text/css" />
		<link href="/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="/static/js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="/static/js/results.js" type="text/javascript"></script>
    </head>

    <body>
        <header>
            <h1>PostCode Wars</h1>
        </header>

        <div id="content">
            <div id="search" class="clearfix">
                <form action="/" id="battle" method="get">
                    <p>
                        <input type="search" name="postcode1" id="battle_postcode1" value="<?= htmlentities($postcode1) ?>" placeholder="Your postcode" />
                        <button type="submit" onclick="document.getElementById('progBar').style.visibility.visible;">Battle!</button>
                        <input type="search" name="postcode2" id="battle_postcode2" value="<?= htmlentities($postcode2) ?>" placeholder="Their postcode" />
                    </p>
                </form>
            </div>
			<div class="progress progress-stripedactive">
			<div class="bar" id="progBar" style="width: 100%;visibility: hidden;"></div>
			</div>

<?php
    if ($result !== null) {
?>

            <ul id="results">
        
<?php
        foreach ($result as $categoryID => $category) {
?>

                <li class="section">
                    <h3><?= htmlentities($category["_name"]) ?></h3>
                    <p class="score-left"><?= htmlentities($category["_score1"]) ?></p>
                    <p class="score-right"><?= htmlentities($category["_score2"]) ?></p>       

<?php
            foreach ($category as $itemID => $item) {
                if ($itemID[0] != "_") {
?>

                    <ul class="stat clearfix">
                        <li class="<?= $item["winner1"] ? "win" : "lose" ?>"><span><?= htmlentities($item["result1"] . " " . $item["units"]) ?></span></li>
                        <li><?= htmlentities($item["name"]) ?></li>
                        <li class="<?= $item["winner1"] ? "lose" : "win" ?>"><span><?= htmlentities($item["result2"] . " " . $item["units"]) ?></span></li>
                    </ul>
<?php
                }
            }
?>
                </li>
<?php
        }
?>
            </ul>

<?php
    }	
?>
		<div class="btn-group dropup">
			<button class="btn">Settings</button>
			<button class="btn dropdown-toggle" data-toggle="dropdown">
			<span class="caret"></span>
			</button>
  <ul class="dropdown-menu">
    <!-- dropdown menu links -->
  </ul>
</div>
        </div>

        <footer>
            <p>PostCode Wars &copy; 2012</p>
        </footer>
    </body>
</html>
