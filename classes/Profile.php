<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';


/**
 * Description of Profile
 *
 * @author Rishi
 */
class Profile {
    
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
    private $loginId;
    private $profileId;
    
    private $newEmail;
    private $reNewEmail;
    private $newPassword;
    private $reNewPassword;
    
    private $connectionManager;
    private $queryManager;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
        
    }
    
    function fetchBasedOnEmail($email) {
        $flag;
        
        $sql = "SELECT * FROM user_login, user_profile WHERE email = '$email' AND user_login.id = user_profile.loginid";
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            if ($row){
                $this->setFName($row['fname']);
                $this->setLName($row['lname']);
                $this->setCompanyName($row['companyname']);
                $this->setAddress1($row['address1']);
                $this->setAddress2($row['address2']);
                $this->setCity($row['city']);
                $this->setState($row['state']);
                $this->setZipcode($row['zipcode']);
                $this->setCountry($row['country']);
                $this->setPhone($row['phone']);
                $this->setEmail($row['email']);
                $this->setPassword($row['password']);
                $this->setClientId($row['loginid']);
                $this->setLoginId($row['loginid']);
                $this->setProfileId($row['id']);
                
                $flag = TRUE;
            }
            else{
                $flag = FALSE;
            }                
        }
        
        return $flag;
    }
    
    function fetchBasedOnLoginid($loginid) {
        $flag;
        
        $sql = "SELECT * FROM user_login, user_profile WHERE user_profile.loginid = '$loginid' AND user_login.id = user_profile.loginid";
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            if ($row){
                $this->setFName($row['fname']);
                $this->setLName($row['lname']);
                $this->setCompanyName($row['companyname']);
                $this->setAddress1($row['address1']);
                $this->setAddress2($row['address2']);
                $this->setCity($row['city']);
                $this->setState($row['state']);
                $this->setZipcode($row['zipcode']);
                $this->setCountry($row['country']);
                $this->setPhone($row['phone']);
                $this->setEmail($row['email']);
                $this->setPassword($row['password']);
                $this->setClientId($row['loginid']);
                $this->setLoginId($row['loginid']);
                $this->setProfileId($row['id']);
                
                $flag = TRUE;
            }
            else{
                $flag = FALSE;
            }                
        }
        
        return $flag;
    }
    
    function update() {
        $flag;
        
        if (!empty($this->newEmail)){
            $isExist = $this->isUserExists();
            if ($isExist){
                $flag = 2;
                return $flag;
            }
        }
        
        if (!empty($this->newPassword)){
            $isValid = $this->verifyPassword();
            if (!$isValid){
                $flag = 3;
                return $flag;
            }
        }
            
        $sql = "UPDATE user_profile, user_login SET fname='$this->fName', lname='$this->lName', companyname='$this->companyName', address1='$this->address1', address2='$this->address2', city='$this->city', state='$this->state', zipcode='$this->zipcode', country='$this->country', phone='$this->phone'";
        if (!empty($this->newEmail)){
            $sql = $sql . ", email = '$this->newEmail', user_login.lastupdated=NOW() ";
        }
        if (!empty($this->newPassword)){
            $sql = $sql . ", password = '".($this->newPassword)."', user_login.lastupdated=NOW() ";
        }
        $sql = $sql . ", user_profile.lastupdated=NOW()  WHERE user_profile.loginid = user_login.id AND user_profile.loginid=$this->loginId";

        $result = $this->queryManager->update($sql);
        if ($result){
            $flag = 1;
            if (!empty($this->newEmail)){
                $_SESSION['email'] = $this->newEmail;
                $this->email = $this->newEmail;
                
                $sql = "UDPATE user_mapping SET email = '$this->newEmail' WHERE loginid = $this->loginId";
                $this->queryManager->update($sql);
            }
            if (!empty($this->newPassword)){
                $this->password = $this->newPassword;
            }
        }
        else{
            $flag = 0;
        }
        
        return $flag;
    }
    
    public function isUserExists() {
        
        $flag;
        
        $sql = "SELECT * FROM user_login WHERE email = '$this->newEmail'";
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
    
    public function verifyPassword() {
        
        $flag;
        
        $sql = "SELECT * FROM user_login WHERE email = '$this->email' AND password = '".($this->password)."'";
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
    
    public function deleteUserProfile($loginid){
        
        $sql = "DELETE FROM user_profile WHERE loginid = $loginid";
        $delete = $this->queryManager->query($sql);

        $sql = "DELETE FROM user_login WHERE id = $loginid";
        $delete = $this->queryManager->query($sql);
        
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

    public function getConnectionManager() {
        return $this->connectionManager;
    }

    public function setConnectionManager($connectionManager) {
        $this->connectionManager = $connectionManager;
    }

    public function getQueryManager() {
        return $this->queryManager;
    }

    public function setQueryManager($queryManager) {
        $this->queryManager = $queryManager;
    }
    
    public function getLoginId() {
        return $this->loginId;
    }

    public function setLoginId($loginId) {
        $this->loginId = $loginId;
    }

    public function getProfileId() {
        return $this->profileId;
    }

    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    public function getNewEmail() {
        return $this->newEmail;
    }

    public function setNewEmail($newEmail) {
        $this->newEmail = $newEmail;
    }

    public function getReNewEmail() {
        return $this->reNewEmail;
    }

    public function setReNewEmail($reNewEmail) {
        $this->reNewEmail = $reNewEmail;
    }

    public function getNewPassword() {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword) {
        $this->newPassword = $newPassword;
    }

    public function getReNewPassword() {
        return $this->reNewPassword;
    }

    public function setReNewPassword($reNewPassword) {
        $this->reNewPassword = $reNewPassword;
    }

    
}

?>
