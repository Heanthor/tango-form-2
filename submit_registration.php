<?php
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("fileEditor.php");

    session_start();
    session_unset();

    // connect to db
    $fe = new FileEditor('login-info.txt');
    $credentials = $fe->readFile();

    $cred = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb", 3306);
    //$cred = new Credentials("localhost", "tango", "tango", "test");
    $connection = new SQLConnector($cred);
    $connection->connect();
    
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $ticket_type = $_POST['status'];
    $type = $_POST['dancer'];

    $partner_fname = "NULL";
    $partner_lname = "NULL";
    $partner_type = "NULL";

    $sqli_ref = $connection->getConnection();
    
    // Only if partner is selected
    if ($_POST['partner'] == "2") {
        $partner_fname = $_POST['fname2'];
        $partner_lname = $_POST['lname2'];
        $partner_type = $_POST['partnerdancerh'];
        
        $partner_fname = "'".mysqli_real_escape_string($sqli_ref, $partner_fname)."'";
        $partner_lname = "'".mysqli_real_escape_string($sqli_ref, $partner_lname)."'";
        $partner_type = "'".mysqli_real_escape_string($sqli_ref, $partner_type)."'";
    }

    // escape strings
    $first_name = mysqli_real_escape_string($sqli_ref, $first_name);
    $last_name = mysqli_real_escape_string($sqli_ref, $last_name);
    $email = mysqli_real_escape_string($sqli_ref, $email);
    $phone = mysqli_real_escape_string($sqli_ref, $phone);
    
    // store into db
    $query = "INSERT INTO `records` (`fname`, `lname`, `email`, `phone`, `tickettype`,
        `dancertype`, `partnerfname`, `partnerlname`, `registerid`) VALUES
        ('$first_name', '$last_name', '$email', '$phone', '$ticket_type',
        '$type', $partner_fname, $partner_lname, NULL);";

    try {
        $connection->insert($query);    
    } catch (Exception $e) {
        $error = $e->getMessage();
        header("Location: error.php?sql_error=$error");
    }

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
