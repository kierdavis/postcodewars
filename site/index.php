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
    <style type="text/css">
        #Text1
        {
            width: 522px;
            height: 25px;
        }
        input.textbox
        {
            border:3px solid #000000;
            text-align: center;
            padding:5px;
             -webkit-border-radius: 4px;
       -moz-border-radius: 4px;
            border-radius: 4px;
        }
        input.textbox:focus 
        {
            border:5px
            outline:none;
            border-color:#96FFFF;
            -webkit-box-shadow: 0px 0px 6px #000000;
        }
        input.button
        {
            width: 120px;
            height: 30px;
        } 
        .style1
        {
            width: 412px;
            height: 166px;
        }
        .style2
        {
            color: #FF0000;
            font-family: Courier;
        }
    </style>
</head>
<body style="font-family: 'Segoe UI'; font-weight: 700; color: #0000CC; text-align: center; font-size: large;" 
    background="small-houses.jpg">
        <p style="color: #FFFFFF; font-family: Courier; text-align: left;">
            RateMyArea: Alpha 0.1.4
                <center><img alt="" class="style1" src="Capture.PNG" /></center>Type in your 
        postcode to get started.</p>
    <form id="textBoxInput"><center><input id="Text1" type="text" class="textbox" style="font-family: Arial Black; font-size: 16px;" /><br />
        </center></form>
       

   <br />
   <br />
        <a href="postcodeConfirm.htm"><center><input id="search" type="button" class="button" value="Search" /></center></a>
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
