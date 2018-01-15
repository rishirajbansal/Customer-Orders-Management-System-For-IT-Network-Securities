<?php

/**
 * Description of ForgotPassword
 *
 * @author Rishi
 */
class ForgotPassword {
    
    private $email;
    
    function __construct($email) {
        $this->email = $email;
    }

    public function retrievePassword($param) {
        
    }


    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

}

?>
