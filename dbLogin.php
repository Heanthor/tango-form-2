<?php
class Credentials {
    public $host;
	public $user;
	public $password;
	public $database;
    public $port;

    function __construct($host, $user, $password, $database, $port = null) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }
}
?>
