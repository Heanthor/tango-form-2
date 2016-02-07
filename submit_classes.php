<?php
    require_once("sqlconnector.php");
    require_once("dbLogin.php");
    require_once("fileEditor.php");

    session_start();

    // receive data
    $selected_classes = implode(",", json_decode($_POST['class_string']));
    $price = intval($_POST['price']);
    $id = $_SESSION['submission_id'];

    // prep connecting to db
    $fe = new FileEditor('login-info.txt');
    $credentials = $fe->readFile();
    $cred = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb", 3306);
    //$cred = new Credentials("localhost", "tango", "tango", "test");
    $connection = new SQLConnector($cred);
    $connection->connect();

    $query = "INSERT INTO `classes` (`registerid`, `classes`, `price`,  `referencenum`, `confirmation`)
            VALUES ('$id', '$selected_classes', '$price', '1234', '50');";

    $connection->insert($query);

    print_r( $selected_classes);
    echo "<br />";
    echo $price;
?>
