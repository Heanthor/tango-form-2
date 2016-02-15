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
?>