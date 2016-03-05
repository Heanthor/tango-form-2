<?php
    require_once("support.php");
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("fileEditor.php");
    require_once("closed.php");
    require_once("convertForm.php");

    echo ("<h1>Class Status</h1>");
    //echo drawTable($result);

    $new_list = array();
    for ($i = 0; $i < 33; $i++) {
        if (in_array($i, array_keys($master_class_list))) {
            $lf = $master_class_list[$i];

            //echo get_classname($i).": [L ".$lf["LEADER"].", F: ".$lf["FOLLOWER"]."]<br>";
            $new_list[get_classname($i)] = array("LEADER" => $lf["LEADER"], "FOLLOWER" => $lf["FOLLOWER"]);
        }
    }

    $table = "<table border=\"1\">";
    $table .= "<th>Class</th><th>Leaders</th><th>Followers</th><th>Balance (L - F)</th>";
    foreach ($new_list as $class => $status) {
        $table .= "<tr><td><strong>$class</strong></td>";
        $table .= "<td>".$status['LEADER']."</td><td>".$status['FOLLOWER']."</td>";
        $table .= "<td>".($status['LEADER'] - $status["FOLLOWER"])."</td></tr>";
    }

    echo $table;
?>
