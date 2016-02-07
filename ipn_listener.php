<?php

include('ipn/ipnlistener.php');
include("fileEditor.php");

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

$listener = new IpnListener();
$listener->use_sandbox = true;

$fe = new FileEditor("log.txt");
try {
    $verified = $listener->processIpn();
} catch (Exception $e) {
    // fatal error trying to process IPN.
    exit(0);
}

if ($verified) {
    $fe->writeToFile($listener->getTextReport());
} else {
    // IPN response was "INVALID"
}

?>