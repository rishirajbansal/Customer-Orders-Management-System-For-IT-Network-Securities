<?php

/**
 * Description of DatabaseQueryManager
 *
 * @author Rishi
 */
class DatabaseQueryManager {
    
    private $connection;
    
    function __construct($connection) {
        $this->connection = $connection;
    }
    
    function query($sql){
        
        $result = mysql_query($sql, $this->connection);
        
        /*if (! $result ) {
            echo 'Could not get data: ' . mysql_error();
            throw new Exception('Could not get data: ' . mysql_error());
        }*/
        
        return $result;
    }
    
    function queryInsertAndGetId($sql){
        
        $result = mysql_query($sql, $this->connection);
        
        if (! $result ) {
            //echo 'Could not insert data: ' . mysql_error();
            //throw new Exception('Could not insert data: ' . mysql_error());
        }
        else{
            $result = mysql_insert_id();
        }
        
        return $result;
    }
    
    function update($sql){
        $result = mysql_query($sql, $this->connection);
          
        return $result;
    }
    
    function fetchSingleRow($result) {
        
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        return $row;
    }
    
    function fetchCount($result){
        $count = mysql_num_rows($result);
        return $count;
    }
            
    function releaseResultSet($result) {
        mysql_free_result($result);        
    }
   
}

?>
