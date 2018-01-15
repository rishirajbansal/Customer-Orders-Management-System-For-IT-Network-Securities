<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'EmailNotification.php';

/**
 * Description of Login
 *
 * @author Rishi
 */
class Login {
    
    private $email;
    private $password;
    
    private $connectionManager;
    private $queryManager;
    private $connection;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    public function login() {
        $flag;
        
        //$password = md5($this->password);
        $password = ($this->password);
        
        $sql = "SELECT * FROM user_login WHERE email = '$this->email' AND password = '$password'";
        $result = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result);
        
        if ($count == 1){
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        $this->queryManager->releaseResultSet($result);
        
        return $flag;
        
    }
    
    public function loginAdmin() {
        $flag;
        
        //$this->password = md5($this->password);
        
        $sql = "SELECT * FROM admin_login WHERE email = '$this->email' AND password = '$this->password'";
        $result = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result);
        
        if ($count >= 1){
            //echo 'Record found with the given credentials';
            //exec('php AutoUpdateOrderStatus.php');
            $flag = TRUE;
        }
        else{
            $flag = FALSE;
        }
        
        $this->queryManager->releaseResultSet($result);
        
        return $flag;
        
    }
    
    public function forgotPassword() {
        $flag;
        
        $sql = "SELECT * FROM user_profile, user_login WHERE user_profile.loginid = user_login.id AND email = '$this->email'";
        $result = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result);
        
        if ($count == 1){
            $row = $this->queryManager->fetchSingleRow($result);
            $password = $row['password'];
            $this->password = $password;
            
            //Send Email
            $mailInfo = array(
                        'fname' => $row["fname"],
                        'lname' => $row["lname"],
                        'email' => $this->email,
                        'password' => $this->password
                        );
            $emailNotification = new EmailNotification();
            $mail = $emailNotification->sendForgotPasswordEmail($mailInfo);

            if ($mail){
                $flag = 4;
            }
            else{
                $flag = 2;
            }
        }
        else{
            $flag = 0;
        }
        
        $this->queryManager->releaseResultSet($result);
        
        return $flag;
        
    }
    
    /*public function forgotPassword() {
        $flag;
        
        $sql = "SELECT * FROM user_profile, user_login WHERE user_profile.loginid = user_login.id AND email = '$this->email'";
        $result = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result);
        
        if ($count == 1){
            $password = $this->generate_password();
            $md5Password = md5($password);
            $this->password = $password;
            
            $sql = "UPDATE user_login SET password = '$md5Password', lastupdated = NOW() WHERE email = '$this->email'";
            $update = $this->queryManager->update($sql);
            if ($update == 1){
                
                $row = $this->queryManager->fetchSingleRow($result);
                
                //Send Email
                $mailInfo = array(
                            'fname' => $row["fname"],
                            'lname' => $row["lname"],
                            'email' => $this->email,
                            'password' => $this->password
                            );
                $emailNotification = new EmailNotification();
                $mail = $emailNotification->sendForgotPasswordEmail($mailInfo);
                
                if ($mail){
                    $flag = 4;
                }
                else{
                    $flag = 2;
                }
            }
            else{
                $flag = 1;
            }
        }
        else{
            $flag = 0;
        }
        
        $this->queryManager->releaseResultSet($result);
        
        return $flag;
        
    }*/
    
    function generate_password($length = 10){
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                  '0123456789';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i=0; $i < $length; $i++)
          $str .= $chars[rand(0, $max)];

        return $str;
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


}

?>
