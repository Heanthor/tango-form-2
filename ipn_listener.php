<?php

require_once('ipn/ipnlistener.php');
require_once("fileEditor.php");
require_once("sqlconnector.php");
require_once("dbLogin.php");

session_start();

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

$listener = new IpnListener();
//$listener->use_sandbox = true;
$listener->use_curl = false;

$fe = new FileEditor('login-info.txt');
$credentials = $fe->readFile();

$cred = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb", 3306);
//$cred = new Credentials("localhost", "tango", "tango", "test");
$connection = new SQLConnector($cred);
$connection->connect();

$fe1 = new FileEditor("log.txt");
$fe1->writeToFile("Run");
try {
    $verified = $listener->processIpn();
} catch (Exception $e) {
    // fatal error trying to process IPN.
    $fe1->writeToFile($e);
    exit(0);
}

if (isset($_SESSION['submission_id'])) {
    $id = $_SESSION['submission_id'];
} else {
    $id = 1; // debug
}

if ($verified) {
    $post_data = $listener->get_post_data();
    $transaction_id = $post_data['txn_id'];
    $payment_gross = $post_data['mc_gross'];
    $status = $post_data['payment_status'];
    $connection->insert("insert into confirmation (registerid, transaction_id, total, payment_status) values($id, $transaction_id, $payment_gross, '$status');");
} else {
    $fe1->writeToFile("Failure");
}

?>