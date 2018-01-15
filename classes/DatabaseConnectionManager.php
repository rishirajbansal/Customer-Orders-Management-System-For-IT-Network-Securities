<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
//include_once '/home/content/22/10609722/html' .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';

/**
 * Description of DatabaseConnectionManager
 *
 * @author Rishi
 */
class DatabaseConnectionManager {
    
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    private $connection;
    
    function __construct() {
        $this->host = Config::$database_host;
        $this->username = Config::$database_username;
        $this->password = Config::$database_password;
        $this->database = Config::$database_name;
        $this->port = Config::$database_port;
    }

    
    function createConnection() {
        
        $host = Config::$database_host;
        $connection = mysql_connect($this->host, $this->username, $this->password);
        
        if (!$connection) {
            die('Could not connect to MySQL: ' . mysqli_connect_error());
        }
        else{
            $this->connection = $connection;
            mysql_select_db($this->database);
            //echo 'Connection established successfully';
                 
        }
    }
    
    function getConnection() {
        return $this->connection;
    }
    
    function returnConnection() {
        if (isset($this->connection) && is_resource($this->connection)) {
            mysql_close($this->connection);        
        }
    }
    
}

?>
