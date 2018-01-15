<?php

/**
 * Description of UserOrder
 *
 * @author Rishi
 */
class UserOrder {
    
    private $id;
    private $loginId;
    private $profileId;
    private $orderId;
    private $userPromotionId;
    private $userOrderStatusId;
    
    
    function __construct() {
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());        
    }
    
    function saveUserOrder() {
        $flag;
        
        $email = $_SESSION['email'];
        
        $sql = "INSERT INTO user_mapping (loginid, profileid, orderid, userorderstatusid, email, createdon, lastupdated) VALUES ($this->loginId, $this->profileId, $this->orderId, $this->userOrderStatusId, '$email', NOW(), NOW())";
        
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

    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
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

    public function getOrderId() {
        return $this->orderId;
    }

    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }
    
    public function getUserPromotionId() {
        return $this->userPromotionId;
    }

    public function setUserPromotionId($userPromotionId) {
        $this->userPromotionId = $userPromotionId;
    }

    public function getUserOrderStatusId() {
        return $this->userOrderStatusId;
    }

    public function setUserOrderStatusId($userOrderStatusId) {
        $this->userOrderStatusId = $userOrderStatusId;
    }




    
    
}

?>
