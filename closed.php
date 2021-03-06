<?php
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("defaultdict.php");
    require_once("fileEditor.php");

    session_start();

    $fe = new FileEditor("login-info.txt");
    $credentials = $fe->readFile();

    $class_limit = 20; // 20 leaders, 20 followers
    $fri_milonga_limit = 150;
    $sat_milonga_limit = 300;
    $sun_milonga_limit = 100;
    $yoga_limit = 100;

    // map table indices to events
    $milongas = array(5, 19, 33);
    $yogas = array(14, 28);
    // start, end ranges for each day
    $day1 = array("min" => 2, "max" => 3);
    $day2 = array("min" => 8, "max" => 17);
    $day3 = array("min" => 22, "max" => 31);

    $cred = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb", 3306);
    //$cred = new Credentials("localhost", "tango", "tango", "test");
    $connection = new SQLConnector($cred);
    $connection->connect();

    try {
        $class_info = $connection->retrieve("SELECT c.classes, r.dancertype, r.partnerfname
                                    FROM records r, classes c, confirmation f
                                    WHERE r.registerid = c.registerid and r.registerid = f.registerid and
                                    f.payment_status = 'Completed';");
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // handle case for one entry in db
    if (isset($class_info['classes'])) {
        $class_info = array($class_info);
    }

    $master_class_list = new Defaultdict(array("LEADER" => 0, "FOLLOWER" => 0)); // <3 from python

    // loop through entries
    //print_r($class_info);
    foreach ($class_info as $entry) {
        $split = explode(",", $entry['classes']);
        if (sizeof($split) > 1) { // empty class string
            if (!$entry['partnerfname']) {
                // No partner
                foreach($split as $class) {
                    $master_class_list[$class][$entry['dancertype']]++;
                }
            } else {
                // Has partner
                foreach($split as $class) {
                    $master_class_list[$class]['LEADER']++;
                    $master_class_list[$class]['FOLLOWER']++;
                }
            }
        }
    }

    $closed_status = array();
    // save into a better format
    $master_class_list = $master_class_list->getContainer();
    // evaluate if class is full from this user's perspective
    $user_type = $_SESSION['dancertype'];
    $partner = $_SESSION['partner_pass'];

    foreach ($master_class_list as $class => $status) {
        $limit = -1;
        if ($class == $milongas[0]) {
            $limit = $fri_milonga_limit;
        } else if ($class == $milongas[1]) {
            $limit = $sat_milonga_limit;
        } else if ($class == $milongas[2]) {
            $limit = $sun_milonga_limit;
        } else if (in_array($class, $yogas)) {
            $limit = $yoga_limit;
        } else {
            $limit = $class_limit;
        }

        // partner only
        if ($partner) {
            if ($status["LEADER"] >= $limit ||
                $status["FOLLOWER"] >= $limit) {
                array_push($closed_status, $class);
            }
        }
        // close class if no more of user type are allowed
        if ($user_type == "LEADER") {
            if ($status["LEADER"] >= $limit) {
                array_push($closed_status, $class);
            }
        } else {
            // follower
            if ($status["FOLLOWER"] >= $limit) {
                array_push($closed_status, $class);
            }
        }

        // Gender balance
        if (!$partner && !(in_array($class, $milongas)) && !(in_array($class, $yogas))) {
            if ($user_type == "LEADER") {
                if ($status["LEADER"] - $status["FOLLOWER"] > 3) {
                    array_push($closed_status, $class);
                }
            } else {
                if ($status["LEADER"] - $status["FOLLOWER"] < -3) {
                    array_push($closed_status, $class);
                }
            }
        }
    }


    //print_r($master_class_list);
    echo(json_encode($closed_status));
?>
