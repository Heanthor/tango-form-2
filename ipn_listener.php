<?php

include('ipn/ipnlistener.php');
require_once("fileEditor.php");
require_once("dbLogin.php");
require_once("sqlconnector.php");


ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

$listener = new IpnListener();
$listener->use_sandbox = true;

$fe2 = new FileEditor('login-info.txt');
$fe1 = new FileEditor("log.txt");
$credentials = $fe2->readFile();

$cred = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb", 3306);
$connection = new SQLConnector($cred);
$connection->connect();

try {
    $verified = $listener->processIpn();
} catch (Exception $e) {
    // fatal error trying to process IPN.
    exit(0);
}

if ($verified) {
    $connection->insert("insert into confirmation values (5, 3, 5)");
    $fe1->writeToFile("Success");
} else {
    $fe1->writeToFile("Failure");
}

?>