<?php
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("fileEditor.php");

    session_start();

    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $ticket_type = $_POST['status'];
    $type = $_POST['dancer'];

    $partner_fname = "NULL";
    $partner_lname = "NULL";
    $partner_type = "NULL";

    // Only if partner is selected
    if ($_POST['partner'] == "2") {
        $partner_fname = "'".$_POST['fname2']."'";
        $partner_lname = "'".$_POST['lname2']."'";

        $partner_type = "'".$_POST['partnerdancerh']."'";
    }

    $fe = new FileEditor('login-info.txt');
    $credentials = $fe->readFile();

    $cred = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb", 3306);
    //$cred = new Credentials("localhost", "tango", "tango", "test");
    $connection = new SQLConnector($cred);
    $connection->connect();

    // store into db
    $query = "INSERT INTO `records` (`fname`, `lname`, `email`, `phone`, `tickettype`,
        `dancertype`, `partnerfname`, `partnerlname`, `registerid`) VALUES
        ('$first_name', '$last_name', '$email', '$phone', '$ticket_type',
        '$type', $partner_fname, $partner_lname, NULL);";

    $connection->insert($query);

    $id = $connection->retrieve("SELECT LAST_INSERT_ID();");

    // store id and partner type into session
    $_SESSION['submission_id'] = $id["LAST_INSERT_ID()"];
    $_SESSION['ticket_type'] = $ticket_type;
    $_SESSION['dancertype'] = $type;
    if ($partner_fname != "NULL") {
        $_SESSION['partner_pass'] = true;
    } else {
        $_SESSION['partner_pass'] = false;
    }

    header("Location: form.php");
?>
