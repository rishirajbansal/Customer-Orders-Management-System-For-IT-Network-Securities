<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'Constants.php';

/**
 * Description of AdminStats
 *
 * @author Rishi
 */
class AdminStats {
    
    private $totalSubscribedCustomers;
    private $totalOrderStatus_0_paymentPending;
    private $totalOrderStatus_0_paymentPendingFrom30Days;
    private $totalOrderStatus_1_paid;
    private $totalOrderStatus_2_approving;
    private $totalOrderStatus_3_processing;
    private $totalOrderStatus_4_done_20daysleft;
    private $totalOrderStatus_5_done_10daysleft;
    private $totalOrderStatus_6_done_5daysleft;
    private $totalOrderStatus_7_done_1dayleft;
    private $totalCreditAvailableCustomers;
    private $totalCreditAmout;
    private $totalOrderStatus_1B_promotionB_paymentPending;
    private $totalOrderStatus_4A_promotionB_done_20daysleft;
    private $totalOrderStatus_5B_promotionB_done_10daysleft;
    private $totalOrderStatus_6B_promotionB_done_5daysleft;
    private $totalOrderStatus_7B_promotionB_done_1dayleft;
    
    private $connectionManager;
    private $queryManager;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    public function fetchStatsData(){
        $sql = "SELECT count(*) AS count FROM user_profile, user_login WHERE user_profile.loginid = user_login.id AND verified = 1";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalSubscribedCustomers = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_NEW."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_0_paymentPending = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_NEW."' AND NOW() > ADDDATE(createdon, 30)";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_0_paymentPendingFrom30Days = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PLACED."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_1_paid = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_APPROVING."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_2_approving = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PROCESSING."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_3_processing = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_READY_20DAYS."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_4_done_20daysleft = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_READY_10DAYS."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_5_done_10daysleft = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_READY_5DAYS."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_6_done_5daysleft = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_READY_1DAY."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_7_done_1dayleft = $row['count'];
        }
        
        $sql = "SELECT count(DISTINCT loginid) AS count FROM user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND paymentstatus = ".Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED." AND creditbalance != 0";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalCreditAvailableCustomers = $row['count'];
        }
        
        $sql = "SELECT sum(creditbalance) AS sum FROM user_orders WHERE paymentstatus = ".Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED;
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalCreditAmout = '$'.number_format($row['sum'], 2);
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAYMENT_PENDING."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_1B_promotionB_paymentPending = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE  paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_4A_promotionB_done_20daysleft = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_5B_promotionB_done_10daysleft = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_6B_promotionB_done_5daysleft = $row['count'];
        }
        
        $sql = "SELECT count(*) AS count FROM user_orders WHERE  paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY."'";
        $result = $this->queryManager->query($sql);
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            $this->totalOrderStatus_7B_promotionB_done_1dayleft = $row['count'];
        }
    }
    
    function __destruct() {
        $this->connectionManager->returnConnection();
    }
    
    public function getTotalSubscribedCustomers() {
        return $this->totalSubscribedCustomers;
    }

    public function setTotalSubscribedCustomers($totalSubscribedCustomers) {
        $this->totalSubscribedCustomers = $totalSubscribedCustomers;
    }

    public function getTotalOrderStatus_0_paymentPending() {
        return $this->totalOrderStatus_0_paymentPending;
    }

    public function setTotalOrderStatus_0_paymentPending($totalOrderStatus_0_paymentPending) {
        $this->totalOrderStatus_0_paymentPending = $totalOrderStatus_0_paymentPending;
    }

    public function getTotalOrderStatus_0_paymentPendingFrom30Days() {
        return $this->totalOrderStatus_0_paymentPendingFrom30Days;
    }

    public function setTotalOrderStatus_0_paymentPendingFrom30Days($totalOrderStatus_0_paymentPendingFrom30Days) {
        $this->totalOrderStatus_0_paymentPendingFrom30Days = $totalOrderStatus_0_paymentPendingFrom30Days;
    }

    public function getTotalOrderStatus_1_paid() {
        return $this->totalOrderStatus_1_paid;
    }

    public function setTotalOrderStatus_1_paid($totalOrderStatus_1_paid) {
        $this->totalOrderStatus_1_paid = $totalOrderStatus_1_paid;
    }

    public function getTotalOrderStatus_2_approving() {
        return $this->totalOrderStatus_2_approving;
    }

    public function setTotalOrderStatus_2_approving($totalOrderStatus_2_approving) {
        $this->totalOrderStatus_2_approving = $totalOrderStatus_2_approving;
    }

    public function getTotalOrderStatus_3_processing() {
        return $this->totalOrderStatus_3_processing;
    }

    public function setTotalOrderStatus_3_processing($totalOrderStatus_3_processing) {
        $this->totalOrderStatus_3_processing = $totalOrderStatus_3_processing;
    }

    public function getTotalOrderStatus_4_done_20daysleft() {
        return $this->totalOrderStatus_4_done_20daysleft;
    }

    public function setTotalOrderStatus_4_done_20daysleft($totalOrderStatus_4_done_20daysleft) {
        $this->totalOrderStatus_4_done_20daysleft = $totalOrderStatus_4_done_20daysleft;
    }

    public function getTotalOrderStatus_5_done_10daysleft() {
        return $this->totalOrderStatus_5_done_10daysleft;
    }

    public function setTotalOrderStatus_5_done_10daysleft($totalOrderStatus_5_done_10daysleft) {
        $this->totalOrderStatus_5_done_10daysleft = $totalOrderStatus_5_done_10daysleft;
    }

    public function getTotalOrderStatus_6_done_5daysleft() {
        return $this->totalOrderStatus_6_done_5daysleft;
    }

    public function setTotalOrderStatus_6_done_5daysleft($totalOrderStatus_6_done_5daysleft) {
        $this->totalOrderStatus_6_done_5daysleft = $totalOrderStatus_6_done_5daysleft;
    }

    public function getTotalOrderStatus_7_done_1dayleft() {
        return $this->totalOrderStatus_7_done_1dayleft;
    }

    public function setTotalOrderStatus_7_done_1dayleft($totalOrderStatus_7_done_1dayleft) {
        $this->totalOrderStatus_7_done_1dayleft = $totalOrderStatus_7_done_1dayleft;
    }

    public function getTotalCreditAvailableCustomers() {
        return $this->totalCreditAvailableCustomers;
    }

    public function setTotalCreditAvailableCustomers($totalCreditAvailableCustomers) {
        $this->totalCreditAvailableCustomers = $totalCreditAvailableCustomers;
    }

    public function getTotalCreditAmout() {
        return $this->totalCreditAmout;
    }

    public function setTotalCreditAmout($totalCreditAmout) {
        $this->totalCreditAmout = $totalCreditAmout;
    }

    public function getTotalOrderStatus_1B_promotionB_paymentPending() {
        return $this->totalOrderStatus_1B_promotionB_paymentPending;
    }

    public function setTotalOrderStatus_1B_promotionB_paymentPending($totalOrderStatus_1B_promotionB_paymentPending) {
        $this->totalOrderStatus_1B_promotionB_paymentPending = $totalOrderStatus_1B_promotionB_paymentPending;
    }

    public function getTotalOrderStatus_4A_promotionB_done_20daysleft() {
        return $this->totalOrderStatus_4A_promotionB_done_20daysleft;
    }

    public function setTotalOrderStatus_4A_promotionB_done_20daysleft($totalOrderStatus_4A_promotionB_done_20daysleft) {
        $this->totalOrderStatus_4A_promotionB_done_20daysleft = $totalOrderStatus_4A_promotionB_done_20daysleft;
    }

    public function getTotalOrderStatus_5B_promotionB_done_10daysleft() {
        return $this->totalOrderStatus_5B_promotionB_done_10daysleft;
    }

    public function setTotalOrderStatus_5B_promotionB_done_10daysleft($totalOrderStatus_5B_promotionB_done_10daysleft) {
        $this->totalOrderStatus_5B_promotionB_done_10daysleft = $totalOrderStatus_5B_promotionB_done_10daysleft;
    }

    public function getTotalOrderStatus_6B_promotionB_done_5daysleft() {
        return $this->totalOrderStatus_6B_promotionB_done_5daysleft;
    }

    public function setTotalOrderStatus_6B_promotionB_done_5daysleft($totalOrderStatus_6B_promotionB_done_5daysleft) {
        $this->totalOrderStatus_6B_promotionB_done_5daysleft = $totalOrderStatus_6B_promotionB_done_5daysleft;
    }

    public function getTotalOrderStatus_7B_promotionB_done_1dayleft() {
        return $this->totalOrderStatus_7B_promotionB_done_1dayleft;
    }

    public function setTotalOrderStatus_7B_promotionB_done_1dayleft($totalOrderStatus_7B_promotionB_done_1dayleft) {
        $this->totalOrderStatus_7B_promotionB_done_1dayleft = $totalOrderStatus_7B_promotionB_done_1dayleft;
    }


}

?>
