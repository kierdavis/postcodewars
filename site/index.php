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
        <script src="/static/js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="/static/js/global.js" type="text/javascript"></script>
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
                        <button type="submit" id="battle_submit">Battle!</button>
                        <input type="search" name="postcode2" id="battle_postcode2" value="<?= htmlentities($postcode2) ?>" placeholder="Their postcode" />
                    </p>
                </form>
            </div>

<?php
    if ($result !== null) {
?>

			<div id="score">
<?php if ($result["_score1"] < $result["_score2"]) { ?>
                <p class="congrats">The left postcode wins!</p>
<?php } else if ($result["_score1"] > $result["_score2"]) { ?>
                <p class="congrats">The right postcode wins!</p>
<?php } else { ?>
                <p class="congrats">It's a draw!</p>
<?php } ?>
				<p>You scored <span><?= htmlentities($result["_score1"]) ?></span> points and they scored <span><?= htmlentities($result["_score2"]) ?></span> points.</p>
			</div>

            <ul id="results">
        
<?php
        foreach ($result as $categoryID => $category) {
            if ($categoryID[0] != "_") {
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
                        <li class="<?= $item["winner1"] ? "win" : "lose" ?>">
                            <span><?= htmlentities($item["result1"]) ?></span>
                            <span class="units"><?= htmlentities($item["units"]) ?></span>
                        </li>
                        
                        <li><?= htmlentities($item["name"]) ?></li>
                        
                        <li class="<?= $item["winner2"] ? "win" : "lose" ?>">
                            <span><?= htmlentities($item["result2"]) ?></span>
                            <span class="units"><?= htmlentities($item["units"]) ?></span>
                        </li>
                    </ul>
<?php
                    }
                }
?>
                </li>
<?php
            }
        }
?>
            </ul>

<?php
    } ?>	<input class="flip" id="settings_button" type="button" value="Settings" />
    <div id="settingsPanel">
    <h1><font face="Arial">Settings</h1>
<h3>Here you can change what goes to war!</h3>
        <hr />
    <div>Crime Score : <input checked id="crimebox" type="checkbox" /></div>
    <div>School Score : <input checked id="schoolbox" type="checkbox" /></div>
    <div>Proximity to A&E Score : <input checked id="aebox" type="checkbox" /></div>
    <div>House Price Score : <input checked id="hpbox" type="checkbox" /></div>
        <hr />
        </div>
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
