<?php
$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
       403 => array('403 Forbidden', 'The server has refused to fulfill your request.'),
       404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
       405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
       408 => array('408 Request Timeout', 'Your browser failed to send a request in the time allowed by the server.'),
       500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
       502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
       504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
);

$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3) {
       $message = 'Please supply a valid status code.';
}


?>
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
<?php // Insert headers here
echo '<h1>'.$title.'</h1>
<p>'.$message.'</p>';
// Insert footer here
?>
    <div></div>
    <div class="bottom_divider" align="center">
        <p><br>You have encountered an error. We appologize for the inconvinience.<br>Please email us at <a href="mailto:Terrapin.Tango.Festival@gmail.com?Subject=Site%20Error <?php echo $codes[$status][0]?>">terrapin.tango.festival@gmail.com</a> and tell us as much as you can about what led you to this page, what internet browser you used, and any other information related to your experience so we can attempt to remedy the situation as quickly as possible.</p>
    </div>
    

     
<div id="copyright" class="container" align="center">
    <p>Copyright (c) 2006-2016 Argentine Tango Club. All rights reserved. | Design by <a href="http://www.freecsstemplates.org/" rel="nofollow">FreeCSSTemplates.org</a>.</p>
</div>
</body>
</html>
