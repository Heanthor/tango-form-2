<?php
    require_once("support.php");
    require_once("dbLogin.php");
    require_once("sqlconnector.php");
    require_once("fileEditor.php");

    $fe = new FileEditor('login-info.txt');
    $credentials = $fe->readFile();

    $user = crypt($credentials[0], "$2a$07ausesomesillystringforsalt$");
    $password = crypt($credentials[1], "$2a$07ausesomesillystringforsalt$");

    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) &&
    hash_equals($user, crypt($_SERVER['PHP_AUTH_USER'], "$2a$07ausesomesillystringforsalt$"))
    && hash_equals($password, crypt($_SERVER['PHP_AUTH_PW'], "$2a$07ausesomesillystringforsalt$"))) {
		drawPage();
	} else {
		header("WWW-Authenticate: Basic realm=\"Example System\"");
		header("HTTP/1.0 401 Unauthorized");
		exit;
	}

    function drawPage() {
        global $credentials;
        $body = "";

        $login = new Credentials("terrapintango.cgpkve9uh8yp.us-east-1.rds.amazonaws.com", $credentials[0], $credentials[1], "tangodb");
        $connector = new SQLConnector($login);

        $connector->connect();
        //print_r($result);

        if (isset($_POST['submit'])) {
            $fields = $_POST['field'];
            $fieldsString = implode(", ", $fields);

            foreach ($fields as $table) {
                if ($table == "ConfirmedRegistrants") {
                    $query = "SELECT r.fname, r.lname, r.partnerfname, r.email, c.classes, c.price
                                                        FROM records r, classes c, confirmation f
                                                        WHERE r.registerid = c.registerid and r.registerid = f.registerid and
                                                        f.payment_status = 'Completed'";
                } else {
                    $query = "select * from $table";
                }

                $result = $connector->retrieve($query);
                $body .= "<h1>".ucfirst($table)."</h1>".drawTable($result);
            }
        } else if (isset($_POST['arbsubmit'])) {
            $query = $_POST['arbtext'];
            $result = $connector->retrieve($query);

            $body .= "<h1>$query</h1>".drawTable($result);
        } else {
            $body =<<<BODY
                <h1> Database Access </h1>

                <form action="admin.php" method="post">
                    <p>
                        <strong>Select tables to display</strong><br />
                        <select name="field[]" multiple="multiple">
                            <option value="records">Records</option>
                            <option value="classes">Classes</option>
                            <option value="confirmation">Confirmation</option>
                            <option value="ConfirmedRegistrants">Confirmed registrations</option>
                        </select>
                    </p>
                    <p>
                        <input type="submit" name="submit" value="Display Tables">
                    </p>
                    <p>
                        <input type='text' name='arbtext'>
                    </p>
                    <p>
                        <input type='submit' name='arbsubmit' value='Submit arbitrary query'>
                    </p>
                </form>
BODY;
        }
        echo generatePage($body, "Admin Panel");
    }
    function drawTable($result) {
        $table ="<table border=\"1\"><tr>";

        foreach(array_keys($result[0]) as $header) {
            $temp = ucfirst($header);
            $table .= "<th align=\"center\">$temp</th>";
        }
        $table .="</tr>";
        //Header done

        foreach(array_values($result) as $value) {
            $row = "<tr>";
            foreach(array_values($value) as $entry) {
                $row .= "<td>$entry</td>";
            }
            $row .= "</tr>";
            $table .= $row;
        }

        $table .= "</table>";
        return $table;
    }
?>
