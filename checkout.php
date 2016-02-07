<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : WellFormed
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20130731

-->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <script source=></script>
    <title>7th annual Terrapin Tango Festival</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Argentine Tango, University of Maryland, ATC, Terrapin Tango Festival" />
    <meta name="description" content="Terrapin Tango Festival" />
    <meta name="author" content="Benjamyn Ward" />
    <!--*******************************-->
    <!-- LOAD FACEBOOK SCRIPT -->
    <meta property="og:title" content="Terrapin Tango Festival" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.studentorg.umd.edu/atc/site/festival/" />
    <meta property="og:image" content="http://www.studentorg.umd.edu/atc/site/festival/tango/TangoCoupleSmall.jpg" />
    <meta property="og:site_name" content="Terrapin Tango Festival" />
    <script type="text/javascript" src="scripts/facebook.js"></script>
    <!--*******************************-->
    <!-- LOAD GOOGLE PLUS SCRIPT -->
    <script type="text/javascript" src="scripts/googlePlus.js"></script>
    <!--*******************************-->
    <script type="text/javascript" src="scripts/paypalButton.js"></script>

    <link href="http://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
    <link href="stylesheets/default.css" rel="stylesheet" type="text/css" media="all" />
    <link href="stylesheets/fonts.css" rel="stylesheet" type="text/css" media="all" />
    <link href="stylesheets/artists-tab-slider.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
<div id="logo" class="container">
    <h1><a href="#">7th annual <span>Terrapin Tango Festival</span></a></h1>
    <p>The University of Maryland, College Park, April 15-17, 2016</p>
</div>
    <div></div>
    <div class="bottom_divider">
        <?php 
            session_start();
            $price = $_SESSION['price'];
            $tax = $price * 0.035;
        ?>
        
       <script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=terrapin.tango.festival@gmail.com" 
    data-button="buynow" 
    data-name="Terrapin Tango Festival Registration" 
    data-amount="<?php echo $price ?>" 
    data-shipping="0" 
    data-tax="<?php echo $tax ?>" 
    data-env=""
></script>
    </div>
    

     
<div id="copyright" class="container">
    <p>Copyright (c) 2006-2016 Argentine Tango Club. All rights reserved. | Design by <a href="http://www.freecsstemplates.org/" rel="nofollow">FreeCSSTemplates.org</a>.</p>
</div>
</body>
</html>
