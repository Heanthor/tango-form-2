<?php
    session_start();

    $ticket_type = $_SESSION['ticket_type'];
    $partner_pass = $_SESSION['partner_pass'];

    $ticket_string = $ticket_type;

    if ($partner_pass) {
        $ticket_string .= " (partner)";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Form</title>
        <link rel="stylesheet" href="stylesheets/normalize.css">
        <link rel='stylesheet' href='stylesheets/main.css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>UMD Tango Schedule</title>
        <meta name="description" content="Tango Schedule 4/17-4/19">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <div class='ticket_type' hidden='hidden'><?php echo($ticket_string)?></div>
        <div class='wrapper'>
            <div class='status_text'>
                <span class='statustext' id='dancertype'>Your ticket type: <span class='red_text'><?php echo($ticket_string."<br />")?></span></span>
                <span class='statustext' id='status'>Select a class to begin</span><br />
                <span class='statustext' id='yourprice'>Your price is: <span class='red_text'>$<span id='numeric_price'>0.00</span></span></span>
            </div>
            <form class='submit_button' action="checkout.php" method="POST">
                <input type="submit" id='submit' name="submit" value="Submit schedule">
                <input type="text" name="class_string" id="class_string" value="" hidden>
                <input type="text" name="price" id="price" value="" hidden>
            </form>
        </div>
        <div class='spacer'></div>
        <div class="scheduleform">
            <table class="center" border="1">
                <tr> <th width=8%>Day</th> <th width=12%>Time</th><th width=40%>Daniela & Luis</th><th width=40%>Juan & Sol</th></tr>
                <tr> <td rowspan='3' class='nonselectable'><h3>Fri<br /> 17 April</h3></td ></tr>
                <tr> <td class='nonselectable'>7-8:30pm</td> <td class=aqua>Finding Sacadas Everywhere<br />(Prince George's Room)</td><td class=alice>Float Like a Butterfly, Sting Like a Bee:<br />Suspension in the Dance<br />(Atrium)</td></tr>
                <tr> <td class='nonselectable'>9pm-1am</td><td colspan='2'><h2>BREAK OUT OF YOUR<br />SHELL MILONGA</h2><br /><span class='location'>Atrium</span></td></tr>
            </table>
            <div class='divider'></div>
            <table class="center" border="1">
                <tr> <th width=8%>Day</th> <th width=12%>Time</th><th width=40%>Daniela & Luis</th><th width=40%>Juan & Sol</th></tr>
                <tr> <td rowspan='5' class='nonselectable'><h3>Sat <br /> 18 April</h3></td> <td class='nonselectable'>12-1:30pm</td> <td class=alice>Impossibly Small Turns: How to <br />Create Space Through the Embrace<br />(Colony Ballroom)</td><td class=aqua>The Dance Between the Beats: <br />Embellishments Hidden in Common Steps<br />(Prince George's Room)</td></tr>
                <tr> <td class='nonselectable'>1:45-3:15pm</td> <td class=alice>Rhythmic Alteraciones for Vals <br />(Colony Ballroom)</td><td class=aqua>The Axis Unhinged: Comfy Volcadas<br />(Prince George's Room)</td></tr>
                <tr> <td class='nonselectable'>3:15-4pm</td><td colspan='2' class=yoga>Yoga for Tango (Prince George's Room)</td></tr>
                <tr> <td class='nonselectable'>4-5:30pm</td><td class=alice>Developing a Floating Walk<br />(Colony Ballroom)</td><td class=aqua>Uncovering the Magic: Exploration of <br />Musicality<br />(Prince George's Room)</td></tr>
                <tr> <td class='nonselectable'>9pm-2am</td><td colspan='2'><h2>TERPSICHORE'S<br />GRAND MILONGA*</h2><br /><span class='location'>Colony Ballroom</span></td></tr>
            </table>
            <div class='divider'></div>
            <table class="center" border="1">
                <tr> <th width=8%>Day</th> <th width=12%>Time</th><th width=40%>Daniela & Luis</th><th width=40%>Juan & Sol</th></tr>
                <tr> <td rowspan='5' class='nonselectable'><h3>Sun <br /> 19 April</h3></td> <td class='nonselectable'>12-1:30pm</td> <td class='aqua'>The Silky Embrace: Effortless and Sensitive Connection<br />(Prince George's Room)</td><td class='alice'>Wax On Wax Off: Mastering Rotation<br />Within Your Own Space<br />(Atrium)</td></tr>
                <tr> <td class='nonselectable'>1:45-3:15pm</td> <td class='aqua'>Making Colgadas Effortless:<br />All Secrets of Colgada Technique<br />(Prince George's Room)</td><td class='alice'>Rapid Fire Footwork for Milonga<br />(Atrium)</td></tr>
                <tr> <td class='nonselectable'>3:15-4pm</td><td colspan='2' class=yoga>Yoga for Tango (Prince George's Room)</td></tr>
                <tr> <td class='nonselectable'>4-5:30pm</td> <td class='aqua'>Ganchos and Wraps<br />(Prince George's Room)</td><td class='alice'>Finding Your Power: Solo and Partner<br />Techniques<br />(Atrium)</td></tr>
                <tr> <td class='nonselectable'>8-12am</td><td colspan='2'><h2>SHELLEBRATION<br />MILONGA</h2><br /><span class='location'>PG Room</span></td></tr>
            </table>
        </div>
    </body>
</html>
