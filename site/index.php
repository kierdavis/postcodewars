<!--
keep files seperate:
- put js specific to this page into site/static/js/index.js
- put global js into site/static/js/global.js
- put css specific to this page into site/static/css/index.css
- put global css into site/static/css/style.css
-->

<html>
    <head>
        <title>Where do you want to live?</title>
        
        <link rel="stylesheet" type="text/css" href="/static/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="/static/css/index.css"/>
        
        <script type="text/css" src="/static/js/jquery-1.7.2.min.js"></script>
        <script type="text/css" src="/static/js/modernizr.js"></script>
        <script type="text/css" src="/static/js/global.js"></script>
        <script type="text/css" src="/static/js/index.js"></script>
    </head>
    
    <body>
        <div id="header">
            <img src="/static/images/header.png"/>
        </div>
        
        <div id="body">
            <form action="" method="get" class="nosubmit-form">
                <label for="postcode-field">Postcode:</label>
                <input type="text" name="postcode" id="postcode-field" size="8" placeholder="Postcode"/>
                
                <!-- TODO: remove "Search" text and add background image -->
                <a href="#" id="search-button">Search</a>
            </form>
        </div>
        
        <div id="footer">
            
        </div>
    </body>
</html>
