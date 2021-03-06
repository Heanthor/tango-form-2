<?php
    require_once("classMapping.php");
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("fileEditor.php");
    require_once("support.php");

    function print_class_info() {
        // SQL
        $fe = new FileEditor('login-info.txt');
        $credentials = $fe->readFile();

        $login = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb");
        $connector = new SQLConnector($login);

        $connector->connect();

        $query = "SELECT r.fname, r.lname, c.classes, c.passtype
                                            FROM records r, classes c, confirmation f
                                            WHERE r.registerid = c.registerid and r.registerid = f.registerid and
                                            f.payment_status = 'Completed'";
        try {
            $result = $connector->retrieve($query);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // END SQL
        $person_classes = array();
        $table = "<table border=\"1\">";
        $table .= "<th>Name</th><th>Pass Type</th><th>Classes</th>";

        echo "<h1>Class Mapping</h1>";
        foreach ($result as $record) {
            //print_r($record);

            $person_classes[$record['fname']." ".$record['lname']] = array($record['passtype'],
                parse_class_string($record['classes']));
            // print_r($translated_classes);
            // echo "<br>";
        }

        //print_r($person_classes);
        foreach ($person_classes as $person => $class_ary) {
            $table .= "<tr><td>$person</td>";
            $table .= "<td>".$class_ary[0]."</td><td>";
            $table .= implode(", ", $class_ary[1])."</td><tr>";
        }

        echo $table;
    }
    
    function parse_class_string($class_string) {
        $c = explode(",", $class_string);
        sort($c);
        $translated_classes = array();

        // make classes into nice words
        foreach ($c as $class) {
            array_push($translated_classes, get_classname($class));
        }

        return $translated_classes;
    }
?>
