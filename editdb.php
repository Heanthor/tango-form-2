<?php
require_once("support.php");
require_once("dbLogin.php");
require_once("sqlconnector.php");
require_once("fileEditor.php");
require_once("classMapping.php");
require_once("personalClassInfo.php");

# update classes set classes = concat('hi', 'bye') where registerid = 108;
$fe = new FileEditor('login-info.txt');
$credentials = $fe->readFile();

$login = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb");
$connector = new SQLConnector($login);

$connector->connect();

echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>";
if (isset($_POST['submit2'])) {
    // page 3
    if (isset($_POST['check'])) {
        $classes_to_remove = $_POST['check'];
    } else {
        $classes_to_remove = array();
    }

    if (isset($_POST['notin'])) {
        $classes_to_add = $_POST['notin'];
    } else {
        $classes_to_add = array();
    }

    $class_string = $_POST['qstring'];
    $registerid = $_POST['registerid'];
    //echo "Class string".$class_string;
    if (sizeof($class_string) > 1) {
        $class_ary = explode(",", $class_string);
    } else {
        $class_ary = array();
    }
    //echo "initial array";
    //print_r($class_ary);
    // print_r($classes_to_remove);
    // print_r($classes_to_add);
    // print_r($class_string);

    $class_ary = array_remove($classes_to_remove, $class_ary);
    $class_ary = array_add($classes_to_add, $class_ary);

    //print_r($class_ary);

    if (sizeof($class_ary) > 1) {
        $new_class_str = implode(",", $class_ary);
    } else if (sizeof($class_ary) == 1){
        $new_class_str = $class_ary[0];
    } else {
        $new_class_str = "";
    }

    $query = "UPDATE classes SET classes = '$new_class_str' WHERE registerid = $registerid;";

    //echo $query;
    try {
        $connector->insert($query);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    echo "Table updated.";
    echo "<p><form action='editdb.php' method=POST><input type='submit' name='xyz' value='Back'></form></p>";

} else if (isset($_POST['submit'])) {
    // page 2
    $registerid = $_POST['radio'];

    $query = "SELECT r.fname, r.lname, c.classes
            FROM  records r, classes c
            WHERE c.registerid = $registerid and r.registerid = c.registerid";
    $table = "<table border ='1' id='table'><th align='center'>Remove</th><th align='center'>Class</th>";

    try {
        $result = $connector->retrieve($query);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $fname = ucfirst($result['fname']);
    $lname = ucfirst($result['lname']);

    $header = "<h1>Classes for $fname $lname</h1>";
    $class_string = $result['classes'];
    $classes = explode(",", $class_string);
    $all_classes = array_keys(get_classes());
    //print_r($all_classes);

    $not_in = array();

    foreach ($all_classes as $c) {
        if (!(in_array($c, $classes))) {
            array_push($not_in, $c);
        }
    }
    //print_r($not_in);
    $select = "<select name='notin[]' id='notin' multiple='multiple'>";
    foreach($not_in as $c) {
        $c2 = get_classname($c);
        $select .= "<option value='$c'>$c2</option>";
    }
    $select .= "</select>";

    foreach ($classes as $class) {
        $c2 = get_classname($class);
        $table .="<tr><td align='center'><input type='checkbox' name='check[]' value='$class'></td><td>$c2</td></tr>";
    }
    $table .= "</table>";

    $maintable = "<table border='0'><th>Currently Enrolled Classes</th><th>Add Classes</th><tr><td>$table</td>";
    $maintable .= "<td>$select</td></tr></table>";

    $form_head = "<form action='editdb.php' method='POST'>";
    $button = "<p><input type='submit' name='submit2' value='Submit'>";
    $hidden = "<input type='text' name='qstring' value='$class_string' hidden='hidden'>";
    $hidden2 = "<input type='text' name='registerid' value='$registerid' hidden='hidden'>";
    echo generatePage($header.$form_head.$hidden.$hidden2.$maintable.$button."</form>", "Edit Page");
} else {
    // Draw table
    $body = "";
    $query = "SELECT r.registerid, r.fname, r.lname, r.partnerfname, r.email, c.classes
                                        FROM records r, classes c, confirmation f
                                        WHERE r.registerid = c.registerid and r.registerid = f.registerid and
                                        f.payment_status = 'Completed'";

    try {
        $result = $connector->retrieve($query);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $body .= "<h1>Select Classes to Edit</h1>";

    $table ="<table border=\"1\"><tr>";
    foreach(array_keys($result[0]) as $header) {
        $temp = ucfirst($header);
        $table .= "<th align=\"center\">$temp</th>";
    }
    $table .= "<th>Edit <br /> Classes</th>";
    $table .="</tr>";
    //Header done
    $table .= "<form action='editdb.php' method='POST'>";
    foreach(array_values($result) as $value) {
        $registerid = -1;
        $i = 0;

        $row = "<tr>";
        foreach(array_values($value) as $entry) {
            if ($i == 0) {
                $registerid = $entry;
            }
            $row .= "<td>$entry</td>";

            $i++;
        }
        $row .= "<td align = 'center'><input type='radio' name= 'radio' value='$registerid' required>";
        $row .= "</tr>";
        $table .= $row;
    }
    $table .= "</table>";
    $table .= "<p><input type='submit' name='submit' value='Submit'></form>";

    $body .= $table;

    echo generatePage($body, "Selection Page");
}

function array_remove($values_to_remove, $array) {
    $temp = array();

    foreach($array as $value) {
        if (!in_array($value, $values_to_remove)) {
            array_push($temp, $value);
        }
    }

    return $temp;
}

function array_add($values_to_add, $array) {
    $temp = $array;

    foreach ($values_to_add as $value) {
        array_push($temp, $value);
    }

    return $temp;
}
?>
<script>
$(function() {
    $("#notin").height($("table").height() - 25);
});
</script>
