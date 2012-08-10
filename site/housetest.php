<form action="/" method="get">
<input type="text" name="location" value="Input your location" />
<input type="submit">
</form>
<?php

$townrefined = $_GET["location"];
			$housepriceunrefined = (file_get_contents("http://api.nestoria.co.uk/api?country=uk&pretty=1&action=metadata&place_name=" . $townrefined . "&encoding=xml"));
			echo $townrefined;
			
			//work out how to get oldest and newest house data, and call them $oldhd and $newhd
			//
			$xmlfile2 = new SimpleXMLElement($housepriceunrefined);
			$oldhp = ($xmlfile2->xpath('opt/response/metadata[@metadata_name="avg_4bed_property_buy_monthly"]/data[@name="2011_m2"]/@avg_price'));
			$newhp = ($xmlfile2->xpath('opt/response/metadata[@metadata_name="avg_4bed_property_buy_monthly"]/data[@name="2012_m2"]/@avg_price'));
			
            logmsg("price-rise-fall-percentage", $housepriceunrefined);
            
			if ($oldhp[0] >= $newhp[0]) {
				$result = ($oldhp[0] / $newhp[0]) * 100;
            }
			else {
				$result = ($newhp[0] / $oldhp[0]) * 100;
            }
            
            // Should return a number - this is the result that is displayed.
            echo $result;
?>
