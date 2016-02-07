<?php
class FileEditor {
    private $filename;
    private $credentials = array();

    function __construct($filename) {
        $this->filename = $filename;
    }

    function findFile() {
        if (file_exists("$this->filename")) {
            return true;
        } else {
            return false;
        }
    }

    function readFile() {
        $filename = "$this->filename";

        $fp = fopen($filename, "r") or die("File could not be opened");
        while (!feof($fp)) {
            //Populate array with names
            $line = fgets($fp);
            array_push($this->credentials, trim($line));
        }

        return $this->credentials;
    }
}
?>
