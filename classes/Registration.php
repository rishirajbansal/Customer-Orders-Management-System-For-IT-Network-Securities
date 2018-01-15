<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';

/**
 * Description of Registration
 *
 * @author Rishi
 */
class Registration {
    
    private $fName;    
    private $lName;    
    private $companyName;    
    private $address1;    
    private $address2;    
    private $city;    
    private $state;    
    private $zipcode;    
    private $country;
    private $phone;
    private $email;
    private $password;
    private $clientId;
    
    private $connectionManager;
    private $queryManager;
    private $connection;
    
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
        
    }
    
    public function isUserExists() {
        
        $flag;
        
        $sql = "SELECT * FROM user_login WHERE email = '$this->email'";
        $result = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result);
        
        if ($count >= 1){
            //echo 'Record found with the email';
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        $this->queryManager->releaseResultSet($result);
        
        return $flag;
    }
    
    public function save() {
        
        $flag;
        
        //$clientId = $this->email . date(dmY);
        //$clientId = md5($clientId);
        //$this->clientId = $clientId;
        
        //$this->password = md5($this->password);
        $this->password = ($this->password);
        
        $sql = "INSERT INTO user_login (email, password, verified, createdon, lastupdated) VALUES ('$this->email', '$this->password', '0', NOW(), NOW())";
        $result = $this->queryManager->queryInsertAndGetId($sql);
        
        if ($result){
            $this->clientId = $result;
            //echo 'Login Id : ' . $result;
            $sql = "INSERT INTO user_profile (loginid, fname, lname, companyname, address1, address2, city, state, zipcode,  country, phone, createdon, lastupdated) VALUES ('$result', '$this->fName', '$this->lName', '$this->companyName', '$this->address1', '$this->address2', '$this->city', '$this->state', '$this->zipcode', '$this->country', '$this->phone', NOW(), NOW())";
            $result = $this->queryManager->queryInsertAndGetId($sql);
            
            if ($result){
                $flag = TRUE;
            }
            else{
                $flag = FALSE;
            }
        }
        else{
            echo 'Id not found';
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    public function verifyUserVerificationKey($clientid){
        $sql = "SELECT * FROM user_login WHERE id = $clientid";
        $result = $this->queryManager->query($sql);
        $row = $this->queryManager->fetchSingleRow($result);
        if ($row){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    public function updateVerification($clientid){
        $sql = "UPDATE user_login SET verified = 1, lastupdated = NOW() WHERE id = $clientid";
        $result = $this->queryManager->update($sql);
        
        if ($result){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }    
    
    
    function __destruct() {
        $this->connectionManager->returnConnection();
    }
        

    public function getFName() {
        return $this->fName;
    }

    public function setFName($fName) {
        $this->fName = $fName;
    }

    public function getLName() {
        return $this->lName;
    }

    public function setLName($lName) {
        $this->lName = $lName;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
    }

    public function getAddress1() {
        return $this->address1;
    }

    public function setAddress1($address1) {
        $this->address1 = $address1;
    }

    public function getAddress2() {
        return $this->address2;
    }

    public function setAddress2($address2) {
        $this->address2 = $address2;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getZipcode() {
        return $this->zipcode;
    }

    public function setZipcode($zipcode) {
        $this->zipcode = $zipcode;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
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

    public function getClientId() {
        return $this->clientId;
    }

    public function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    
}

?>
