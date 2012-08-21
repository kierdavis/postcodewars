<?php
    error_reporting(-1);
    require_once "../lib/include.php";
    require_once "../lib/util.php";
    
    require_once "../lib/search.php";
    error_reporting(-1);
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
        <title>Postcode Wars</title>

        <link href="/static/css/results.css" rel="stylesheet" type="text/css" />    
        <link href="/static/css/jacob.css" rel="stylesheet" type="text/css" />
        <script src="/static/js/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script src="/static/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="/static/js/global.js" type="text/javascript"></script>
		
    </head>

    <body>
		<div id="settingsPanel">
			<h2>Settings</h2>
			<p><b>Here you can change what goes to war!</b></p>
			<ul>
				<li>House Price Score <input checked="checked"  id="house-price-visibility" type="checkbox" /></li>
				<li>Amenities Score <input checked="checked"  id="amenities-visibility" type="checkbox" /></li>
				<li>Crime Score <input checked="checked" id="crime-visibility" type="checkbox" /></li>
				<!--<li>Proximities Score <input checked="checked"  id="proximities-visibility" type="checkbox" /></li>-->
			</ul>
		</div>
        
        <header>
            <h1>Postcode Wars</h1>
        </header>
        
		<p id="yrs-win">Awarded &quot;Best Example of Coding&quot; at <a href="http://hacks.rewiredstate.org/events/yrs2012/postcode-wars">Young Rewired State</a></p>

        <div id="content">
            <div id="search" class="clearfix">
                <form action="/" id="battle" method="get">
					<p class="clearfix">
                        <input type="text" name="postcode1" id="battle_postcode1" tabindex="1" value="<?= htmlentities($postcode1) ?>" placeholder="Postcode" />
                        <button type="submit" tabindex="3" id="battle_submit">Battle!</button>
                        <input type="text" name="postcode2" tabindex="2" id="battle_postcode2" value="<?= htmlentities($postcode2) ?>" placeholder="Postcode" />
                    </p>
                </form>
            </div>
        
        <div class="notices">
	        <p id="textnumber">You can also text two postcodes to +44 (0)203 322 4545!</p>
			<p id="crimeapology">The crime category cannot show Scottish crime data yet. For this, we apologise.</p>
		</div>

<?php
    if ($result !== null) {
?>

			<div id="score">
<?php if ($result["_score1"] > $result["_score2"]) { ?>
                <p class="congrats"><span><?= htmlentities($postcode1) ?></span> wins!</p>
<?php } else if ($result["_score1"] < $result["_score2"]) { ?>
                <p class="congrats"><span><?= htmlentities($postcode2) ?></span> wins!</p>
<?php } else { ?>
                <p class="congrats">It's a draw!</p>
<?php } ?>
				<p><span><?= htmlentities($postcode1) ?></span> scored <span id="leftScore"><?= htmlentities($result["_score1"]) ?></span> points and <span><?= htmlentities($postcode2) ?></span> scored <span id="rightScore"><?= htmlentities($result["_score2"]) ?></span> points.</p>
			</div>

            <ul id="results">
        
<?php
        foreach ($result as $categoryID => $category) {
            if ($categoryID[0] != "_") {
?>

				<li class="section" id="<?= $categoryID ?>">
					<h3><?= htmlentities($category["_name"]) ?></h3>
					<p class="score-left <?= $category['_score1'] >= $category['_score2'] ? ($category['_score1'] == $category['_score2'] ? 'draw' : 'win' ) : 'lose' ?>"><?= htmlentities($category["_score1"]) ?></p>
					<p class="score-right <?= $category['_score2'] >= $category['_score1'] ? ($category['_score1'] == $category['_score2'] ? 'draw' : 'win' ) : 'lose' ?>"><?= htmlentities($category["_score2"]) ?></p>

<?php
                foreach ($category as $itemID => $item) {
                    if ($itemID[0] != "_") {
                        if (substr($itemID, -7) == "-nearby") {
                            if ($item["result1"] >= 20) {
                                $item["result1"] = "20+";
                            }
                            if ($item["result2"] >= 20) {
                                $item["result2"] = "20+";
                            }
                        }
                        
                        if ($item["winner1"] == $item["winner2"]) {
?>

                    <ul class="stat draw clearfix">
                        <li>
                            <span><?= htmlentities($item["result1"]) ?></span>
                            <span class="units"><?= htmlentities($item["units"]) ?></span>
                        </li>
                        
                        <li class="description"><?= htmlentities($item["name"]) ?></li>
                        
                        <li>
                            <span><?= htmlentities($item["result2"]) ?></span>
                            <span class="units"><?= htmlentities($item["units"]) ?></span>
                        </li>
                    </ul>

<?php } else { ?>

                    <ul class="stat clearfix">
                        <li class="<?= $item["winner1"] ? "win" : "lose" ?>">
                            <span><?= htmlentities($item["result1"]) ?></span>
                            <span class="units"><?= htmlentities($item["units"]) ?></span>
                        </li>
                        
                        <li class="description"><?= htmlentities($item["name"]) ?></li>
                        
                        <li class="<?= $item["winner2"] ? "win" : "lose" ?>">
                            <span><?= htmlentities($item["result2"]) ?></span>
                            <span class="units"><?= htmlentities($item["units"]) ?></span>
                        </li>
                    </ul>
<?php
                        }
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
</div>
        </div>

        <footer>
            <p>PostCode Wars &copy; 2012 - <a href="mailto:jacob.walker.94+pcw@gmail.com">Get in touch</a></p>
        </footer>
        
        <script type="text/javascript">
		<!--
			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-34052940-1']);
			  _gaq.push(['_trackPageview']);
			
			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
		-->		
		</script>
    </body>
</html>
