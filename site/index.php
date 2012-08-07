<!--
keep files seperate:
- put js specific to this page into site/static/js/index.js
- put global js into site/static/js/global.js
- put css specific to this page into site/static/css/index.css
- put global css into site/static/css/style.css
-->

<html>
<head>
    <title>RateMyArea</title>
    <script type="text/javascript">
            $(document).ready(function () {
            function pulsate() {
                $(".pulsate").animate({ opacity: 0.2 }, 1200, 'linear')
                     .animate({ opacity: 1 }, 1200, 'linear', pulsate)
                     .click(function () {
                         $(this).animate({ opacity: 1 }, 1200, 'linear');
                         $(this).stop();
                     });
            }

            pulsate();
            </script>
    <script src="static/js/index.js"></script>
</head>
<body style="font-family: 'Segoe UI'; font-weight: 700; color: #0000CC; text-align: center; font-size: large;" 
    background="small-houses.jpg">
        <p style="color: #FFFFFF; font-family: Courier; text-align: left;">
            RateMyArea: Alpha 0.1.4 <br/>
            Type in your postcode to get started.</p>
    	<form id="textBoxInput">
    		<center>
    			<input id="Text1" type="text" class="textbox" class="pulsate" style="font-family: Arial Black; font-size: 16px;" />
    			<br />
        	</center>
        </form>
		<br />
		<br />
        <a href="postcodeConfirm.htm">
        	<center>
        		<input id="search" type="button" class="button" value="Search" />
        	</center>
       	</a>
        <p>
        &nbsp;</p>
        <p>
        &nbsp;</p>
        <p>
        &nbsp;</p>
        <div style="font-family: Courier; color: #FFFFFF">
            <p class="style2" style="text-align: center; font-size: medium">
                &nbsp;</p>
        </div>
</body>
</html>
