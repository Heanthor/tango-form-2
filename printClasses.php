<?php
    require_once("support.php");
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("fileEditor.php");
    require_once("closed.php");

    $fe = new FileEditor('login-info.txt');
    $credentials = $fe->readFile();
    
    $login = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb");
    $connector = new SQLConnector($login);

    $connector->connect();
    
    $query = "select * from records";
    $result = $connector->retrieve($query);
    echo ("<h1>Class Status</h1>");
    //echo drawTable($result);
    
    for ($i = 0; $i < 33; $i++) {
        if (in_array($i, array_keys($master_class_list))) {
            $lf = $master_class_list[$i];
            
            echo "Class ".$i.": [L ".$lf["LEADER"].", F: ".$lf["FOLLOWER"]."]<br>";
        }
    }
    
?>