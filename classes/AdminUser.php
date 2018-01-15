<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';

/**
 * Description of AdminUser
 *
 * @author Rishi
 */
class AdminUser {
    
    private $email;
    private $password;
    
    private $admins;
    private $ftpAddress;
    private $ftpUser;
    private $ftpPassword;
    
    private $connectionManager;
    private $queryManager;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    public function fetchAdmins() {
        $flag;
        
        $sql = "SELECT * FROM admin_login";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $admins = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $record = array(
                            'email' => $row['email'],
                            'password' => $row['password']
                           );
                $admins[$ctr] = $record;
                $ctr+=1;
            }
            $this->setAdmins($admins);
            
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
        
    }
    
    public function fetchFTPDetails() {
        $flag;
        
        $sql = "SELECT * FROM admin_ftp";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            if ($row){
                $this->setFtpAddress($row['ftpaddress']);
                $this->setFtpUser($row['ftpuser']);
                $this->setFtpPassword($row['ftppassword']);
                
                $flag = TRUE;
            }
            else{
                $flag = FALSE;
            }
        }
        else{
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function saveAdminUsers() {
        $flag;
        
        $sql = "DELETE FROM admin_login";
        $result = $this->queryManager->query($sql);
        
        foreach ($this->getAdmins() as $record) {
            $email = $record['email'];
            $password = $record['password'];
            
            $sql = "INSERT INTO admin_login (email, password, createdon, lastupdated) VALUES ('$email', '$password', NOW(), NOW())";
            $result = $this->queryManager->queryInsertAndGetId($sql);
            
            if ($result){
                $flag = TRUE;
            }
            else{
                $flag = FALSE;
            }
        }
                
        return $flag;
    }
    
    public function saveFTPDetails() {
        $flag;
        
        $sql = "DELETE FROM admin_ftp";
        $result = $this->queryManager->query($sql);
        
        $sql = "INSERT INTO admin_ftp (ftpaddress, ftpuser, ftppassword, createdon, lastupdated) VALUES ('$this->ftpAddress', '$this->ftpUser', '$this->ftpPassword', NOW(), NOW())";
        $result = $this->queryManager->queryInsertAndGetId($sql);

        if ($result){
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
                
        return $flag;
    }
    
    function __destruct() {
        $this->connectionManager->returnConnection();
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getAdmins() {
        return $this->admins;
    }

    public function setAdmins($admins) {
        $this->admins = $admins;
    }
    
    public function getFtpAddress() {
        return $this->ftpAddress;
    }

    public function setFtpAddress($ftpAddress) {
        $this->ftpAddress = $ftpAddress;
    }

    public function getFtpUser() {
        return $this->ftpUser;
    }

    public function setFtpUser($ftpUser) {
        $this->ftpUser = $ftpUser;
    }

    public function getFtpPassword() {
        return $this->ftpPassword;
    }

    public function setFtpPassword($ftpPassword) {
        $this->ftpPassword = $ftpPassword;
    }



    
}

?>
