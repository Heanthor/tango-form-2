<?php

function generatePage($body, $title="Example", $stylesheet="") {
    $page = <<<EOPAGE
    <!doctype html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>$title</title>
            $stylesheet
        </head>

        <body>
            $body
        </body>
    </html>
EOPAGE;

    return $page;
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
