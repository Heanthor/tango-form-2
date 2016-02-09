<?php

require_once('ipn/ipnlistener.php');
require_once("fileEditor.php");
require_once("sqlconnector.php");
require_once("dbLogin.php");


ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

$listener = new IpnListener();
$listener->use_sandbox = true;
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

if ($verified) {
    $fe1->writeToFile($listener->get_transaction_id());
    $connection->insert("insert into confirmation values(69, 4, 4)");
} else {
    $fe1->writeToFile("Failure");
}

?>