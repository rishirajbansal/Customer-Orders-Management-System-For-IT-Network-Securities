<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'EmailNotification.php';
include_once 'Constants.php';

//include_once '/home/content/22/10609722/html' .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';


/**
 * Description of CronOrder
 *
 * @author Rishi
 */
class CronOrder {
    
    private $orderNo;
    private $projectName;
    private $noOfRecords;
    private $totalPrice;
    private $promoCode;
    private $creditBalance;
    private $timer;
    private $createdOn;
    private $lastUpdated;
    private $status;
    private $paymentStatus;
    private $processingTime;
    private $fileName;
    private $discount;
    private $demofile;
    
    private $clientid;
    private $previousStatus;
    private $timePending;
    private $interval;
    private $promoType;
    private $totalCreditedBalance;
    private $totalCreditedBalanceLeft;
    private $isCreditAvailableMode;
    private $paymentEmail;
    private $payflowAmount;
    private $amountcharged;
    
    private $userOrderStatusId;
    
    private $comments;
    private $status1_delete;
    private $status1_apple;
    private $status1_demo;
    private $status2_delete;
    private $status2_apple;
    private $status2_demo;
    private $status3_delete;
    private $status3_apple;
    private $status3_demo;
    private $status4_delete;
    private $status4_apple;
    private $status4_demo;
    private $status4A_delete;
    private $status4A_apple;
    private $status4A_demo;
    private $status4B_delete;
    private $status4B_apple;
    private $status4B_demo;
    private $status5_delete;
    private $status5_apple;
    private $status5_demo;
    private $status6_delete;
    private $status6_apple;
    private $status6_demo;
    private $status7_delete;
    private $status7_apple;
    private $status7_demo;
    
    private $payAmount;
    
    private $cancelReason;
      
    private $cancelledProjects;
    private $allProjects;
    
    private $cleanFiles;
    private $demoFiles;
    private $pdfFiles;
    
    private $connectionManager;
    private $queryManager;
    
    private $message;
    
    function __construct() {
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());        
    }
    
    
    
    public function updateTimerActivatedOrders(){
        $updateTostatus5BOrders = "";
        $updateTostatus6BOrders = "";
        $updateTostatus7BOrders = "";
        $updateTostatus5Orders = "";
        $updateTostatus6Orders = "";
        $updateTostatus7Orders = "";
        $removeBOrders = array();
        $removeOrders = array();
        
        
        $sql = "SELECT * FROM user_orders, user_mapping WHERE user_orders.id = user_mapping.orderid AND paymentstatus != ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED." AND timer = 1 AND timeractivatedon IS NOT NULL";
        $result = $this->queryManager->query($sql);
        
        if ($result){
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $promotype = "";
                
                $isPromtionApplied = $row['promotionapplied'];
                if ($isPromtionApplied && $isPromtionApplied == 1){
                    $userpromotionid = $row['userpromotionid'];
                    $sql = "SELECT * FROM user_promotions WHERE iduser_promotions = $userpromotionid";
                    $result2 = $this->queryManager->query($sql);
                    if ($result2){
                        $row2 = $this->queryManager->fetchSingleRow($result2);
                        if ($row2){
                            $promotype = $row2['promotype'];
                        }
                    }
                }
                
                $timerActivatedTimestampTemp = $row['timeractivatedon'];
                $timerActivatedTimestampTemp = strtotime($timerActivatedTimestampTemp);
                $timerActivatedTimestamp = new DateTime();
                $timerActivatedTimestamp->setTimestamp($timerActivatedTimestampTemp);
                
                $currentTimestamp = new DateTime();
                
                $orderStatus = $row['status'];
                
                if ($promotype == 'B'){
                    if ($orderStatus == "4A" || $orderStatus == "4B"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 10){
                            $updateTostatus5BOrders = $updateTostatus5BOrders . $row['orderid'] . ',';
                        }
                    }
                    else if ($orderStatus == "5B"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 5){
                            $updateTostatus6BOrders = $updateTostatus6BOrders . $row['orderid'] . ',';
                        }
                    }
                    else if ($orderStatus == "6B"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 1){
                            $updateTostatus7BOrders = $updateTostatus7BOrders . $row['orderid'] . ',';
                        }
                    }
                    else if ($orderStatus == "7B"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 0){
                            $record = array('orderid' => $row['orderid'], 'clientid' => $row['loginid']);
                            
                            array_push($removeBOrders, $record);
                        }
                    }
                }
                else{
                    if ($orderStatus == "4"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 10){
                            $updateTostatus5Orders = $updateTostatus5Orders . $row['orderid'] . ',';
                        }
                    }
                    else if ($orderStatus == "5"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 5){
                            $updateTostatus6Orders = $updateTostatus5Orders . $row['orderid'] . ',';
                        }
                    }
                    else if ($orderStatus == "6"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 1){
                            $updateTostatus7Orders = $updateTostatus5Orders . $row['orderid'] . ',';
                        }
                    }
                    else if ($orderStatus == "7"){
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $interval = $currentTimestamp->diff($timerActivatedTimestamp);
                        $timePending = (int)$interval->format('%R%a');

                        if ($timePending <= 0){
                            $record = array('orderid' => $row['orderid'], 'clientid' => $row['loginid']);
                            
                            array_push($removeOrders, $record);
                        }
                    }
                }
                
                
            }
            
            if (!empty($updateTostatus5BOrders)){
                $updateTostatus5BOrders = substr($updateTostatus5BOrders, 0, strlen($updateTostatus5BOrders)-1);
                
                //Update orders to status '5B'
                $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS."' , lastupdated = NOW() WHERE id IN ($updateTostatus5BOrders)";
                $update = $this->queryManager->query($sql);
                
                if ($update){
                    $orderArr = explode(',', $updateTostatus5BOrders);
                    $this->sendMailForTimerActivatedOrders($orderArr, Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS);
                }
            }
            if (!empty($updateTostatus6BOrders)){
                $updateTostatus6BOrders = substr($updateTostatus6BOrders, 0, strlen($updateTostatus6BOrders)-1);
                
                //Update orders to status '6B'
                $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS."' , lastupdated = NOW() WHERE id IN ($updateTostatus6BOrders)";
                $update = $this->queryManager->query($sql);
                
                if ($update){
                    $orderArr = explode(',', $updateTostatus6BOrders);
                    $this->sendMailForTimerActivatedOrders($orderArr, Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS);
                }
            }
            if (!empty($updateTostatus7BOrders)){
                $updateTostatus7BOrders = substr($updateTostatus7BOrders, 0, strlen($updateTostatus7BOrders)-1);
                
                //Update orders to status '7B'
                $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY."' , lastupdated = NOW() WHERE id IN ($updateTostatus7BOrders)";
                $update = $this->queryManager->query($sql);
                
                if ($update){
                    $orderArr = explode(',', $updateTostatus7BOrders);
                    $this->sendMailForTimerActivatedOrders($orderArr, Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY);
                }
            }
            if (!empty($updateTostatus5Orders)){
                $updateTostatus5Orders = substr($updateTostatus5Orders, 0, strlen($updateTostatus5Orders)-1);
                
                //Update orders to status '5'
                $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_READY_10DAYS."' , lastupdated = NOW() WHERE id IN ($updateTostatus5Orders)";
                $update = $this->queryManager->query($sql);
                
                if ($update){
                    $orderArr = explode(',', $updateTostatus5Orders);
                    $this->sendMailForTimerActivatedOrders($orderArr, Constants::ORDER_STATUS_READY_10DAYS);
                }
            }
            if (!empty($updateTostatus6Orders)){
                $updateTostatus6Orders = substr($updateTostatus6Orders, 0, strlen($updateTostatus6Orders)-1);
                
                //Update orders to status '6'
                $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_READY_5DAYS."' , lastupdated = NOW() WHERE id IN ($updateTostatus6Orders)";
                $update = $this->queryManager->query($sql);
                
                if ($update){
                    $orderArr = explode(',', $updateTostatus6Orders);
                    $this->sendMailForTimerActivatedOrders($orderArr, Constants::ORDER_STATUS_READY_5DAYS);
                }
            }
            if (!empty($updateTostatus7Orders)){
                $updateTostatus7Orders = substr($updateTostatus7Orders, 0, strlen($updateTostatus7Orders)-1);
                
                //Update orders to status '7'
                $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_READY_1DAY."' , lastupdated = NOW() WHERE id IN ($updateTostatus7Orders)";
                $update = $this->queryManager->query($sql);
                
                 if ($update){
                    $orderArr = explode(',', $updateTostatus7Orders);
                    $this->sendMailForTimerActivatedOrders($orderArr, Constants::ORDER_STATUS_READY_1DAY);
                }
            }
            if (!empty($removeBOrders)){
                foreach ($removeBOrders as $record) {
                    
                    $this->setClientid($record['clientid']);
                    $this->setOrderNo($record['orderid']);
                    
                    $this->deleteUserOrders();
                }
            }
            if (!empty($removeOrders)){
                foreach ($removeOrders as $record) {
                    
                    $this->setClientid($record['clientid']);
                    $this->setOrderNo($record['orderid']);
                    
                    $this->deleteUserOrders();
                }
            }
        }
        
        $sql = "INSERT INTO cron_log (time) VALUES (NOW())";
        $result1 = $this->queryManager->query($sql);
    }
    
    function sendMailForTimerActivatedOrders($orderArr, $status){
        
        foreach ($orderArr as $orderid) {

            $sql = "SELECT * FROM user_profile, user_orders, user_mapping WHERE user_orders.id = user_mapping.orderid AND user_profile.id = user_mapping.profileid AND orderid = $orderid";
            $result1 = $this->queryManager->query($sql);
            if ($result1){
                $row = $this->queryManager->fetchSingleRow($result1);

                $mailInfo = array(
                     'fname' => $row['fname'],
                     'lname' => $row['lname'],
                     'email' => $row['email'],
                     'orderno' => $orderid,
                     'project' => $row['projectname'],
                     );

                $emailNotification = new EmailNotification();
                $mail = $emailNotification->sendStatusEmail($mailInfo, $status);
            }
        }
    }
    
    public function deleteUserOrders(){
        $flag = -1;
        $status = '';
        
      $orderid = $this->getOrderNo();
       $clientid = $this->getClientid();
       
       $sql = "SELECT * from user_orders WHERE id = $orderid";
       $result = $this->queryManager->query($sql);
       if ($result){
           $row = $this->queryManager->fetchSingleRow($result);
           $status = $row['status'];
       }
       
       if ($status != Constants::ORDER_STATUS_NEW){
           $sql = "UPDATE user_orders SET paymentstatus = ". Constants::ORDER_PAYMENT_STATUS_PAID_DELETED .", lastupdated = NOW() WHERE id = $orderid";
            $update = $this->queryManager->update($sql);

            if ($update){
                //Remove user folder
                 $folderName = $orderid . '-' . $clientid;

                 $customerDirLocation = Config::getCustomerFolder_location().$folderName;

                 if (file_exists($customerDirLocation)){
                     $deleteFiles = $customerDirLocation.DIRECTORY_SEPARATOR.'*.*';
                     foreach (glob($deleteFiles) as $filename){
                         unlink($filename);
                     }
                     rmdir($customerDirLocation);
                     $flag = 2;
                 }
                 else{
                     $flag = 1;
                 }
            }
            else{
                $flag = 0;
            }
       }
       else{
           $flag = $this->deleteUserOrdersPermanently();
       }
       
       return $flag;
       
    }
    
    public function deleteUserOrdersPermanently(){
       $flag = -1;
        
       $orderid = $this->getOrderNo();
       $clientid = $this->getClientid();
       
       //Check if promotion exists for the user
       $sql = "SELECT * FROM user_mapping WHERE orderid = $orderid";
       $result = $this->queryManager->query($sql);
       if ($result){
           $row = $this->queryManager->fetchSingleRow($result);
           
           if ($row){
               $promotionId = $row['userpromotionid'];
                $orderStatusId = $row['userorderstatusid'];
                $promotionId = $row['userpromotionid'];
                $loginid = $row['loginid'];
                $profileid = $row['profileid'];

                if ($promotionId && $promotionId != 0){
                    $sql = "DELETE FROM user_promotions WHERE iduser_promotions = $promotionId";
                    $delete = $this->queryManager->query($sql);
                }

                //Delete user order status from 'A' type
                $sql = "DELETE FROM user_orders_status WHERE iduser_orders_status = $orderStatusId";
                $delete = $this->queryManager->query($sql);

                //Delete user order status from 'B' type
                $sql = "DELETE FROM user_orders_promotion_b_status WHERE iduser_orders_status = $orderStatusId";
                $delete = $this->queryManager->query($sql);

                $sql = "DELETE FROM user_mapping WHERE orderid = $orderid";
                $delete = $this->queryManager->query($sql);

                $sql = "DELETE FROM user_orders WHERE id = $orderid";
                $delete = $this->queryManager->query($sql);

                //Remove user folder
                $folderName = $orderid . '-' . $clientid;

                $customerDirLocation = Config::getCustomerFolder_location().$folderName;

                if (file_exists($customerDirLocation)){
                    $deleteFiles = $customerDirLocation.DIRECTORY_SEPARATOR.'*.*';
                    foreach (glob($deleteFiles) as $filename){
                        unlink($filename);
                    }
                    rmdir($customerDirLocation);
                    $flag = 2;
                }
                else{
                    $flag = 1;
                }
           }
           
       }
       else{
           $flag = 0;
       }
       
       return $flag;
    }
    
        
       
    function __destruct() {
        if ($this->connectionManager->getConnection()){
            $this->connectionManager->returnConnection();
        }
    }
     
    public function getOrderNo() {
        return $this->orderNo;
    }

    public function setOrderNo($orderNo) {
        $this->orderNo = $orderNo;
    }

    public function getProjectName() {
        return $this->projectName;
    }

    public function setProjectName($projectName) {
        $this->projectName = $projectName;
    }

    public function getNoOfRecords() {
        return $this->noOfRecords;
    }

    public function setNoOfRecords($noOfRecords) {
        $this->noOfRecords = $noOfRecords;
    }

    public function getTotalPrice() {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
    }

    public function getPromoCode() {
        return $this->promoCode;
    }

    public function setPromoCode($promoCode) {
        $this->promoCode = $promoCode;
    }

    public function getCreditBalance() {
        return $this->creditBalance;
    }

    public function setCreditBalance($creditBalance) {
        $this->creditBalance = $creditBalance;
    }

    public function getTimer() {
        return $this->timer;
    }

    public function setTimer($timer) {
        $this->timer = $timer;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
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
    
    public function getProcessingTime() {
        return $this->processingTime;
    }

    public function setProcessingTime($processingTime) {
        $this->processingTime = $processingTime;
    }
    
    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }
    
    public function getCancelledProjects() {
        return $this->cancelledProjects;
    }

    public function setCancelledProjects($cancelledProjects) {
        $this->cancelledProjects = $cancelledProjects;
    }
    
    public function getAllProjects() {
        return $this->allProjects;
    }

    public function setAllProjects($allProjects) {
        $this->allProjects = $allProjects;
    }
    public function getPaymentStatus() {
        return $this->paymentStatus;
    }

    public function setPaymentStatus($paymentStatus) {
        $this->paymentStatus = $paymentStatus;
    }

    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }
    
    public function getStatus1_delete() {
        return $this->status1_delete;
    }

    public function setStatus1_delete($status1_delete) {
        $this->status1_delete = $status1_delete;
    }

    public function getStatus1_apple() {
        return $this->status1_apple;
    }

    public function setStatus1_apple($status1_apple) {
        $this->status1_apple = $status1_apple;
    }

    public function getStatus1_demo() {
        return $this->status1_demo;
    }

    public function setStatus1_demo($status1_demo) {
        $this->status1_demo = $status1_demo;
    }

    public function getStatus2_delete() {
        return $this->status2_delete;
    }

    public function setStatus2_delete($status2_delete) {
        $this->status2_delete = $status2_delete;
    }

    public function getStatus2_apple() {
        return $this->status2_apple;
    }

    public function setStatus2_apple($status2_apple) {
        $this->status2_apple = $status2_apple;
    }

    public function getStatus2_demo() {
        return $this->status2_demo;
    }

    public function setStatus2_demo($status2_demo) {
        $this->status2_demo = $status2_demo;
    }

    public function getStatus3_delete() {
        return $this->status3_delete;
    }

    public function setStatus3_delete($status3_delete) {
        $this->status3_delete = $status3_delete;
    }

    public function getStatus3_apple() {
        return $this->status3_apple;
    }

    public function setStatus3_apple($status3_apple) {
        $this->status3_apple = $status3_apple;
    }

    public function getStatus3_demo() {
        return $this->status3_demo;
    }

    public function setStatus3_demo($status3_demo) {
        $this->status3_demo = $status3_demo;
    }

    public function getStatus5_delete() {
        return $this->status5_delete;
    }

    public function setStatus5_delete($status5_delete) {
        $this->status5_delete = $status5_delete;
    }

    public function getStatus5_apple() {
        return $this->status5_apple;
    }

    public function setStatus5_apple($status5_apple) {
        $this->status5_apple = $status5_apple;
    }

    public function getStatus5_demo() {
        return $this->status5_demo;
    }

    public function setStatus5_demo($status5_demo) {
        $this->status5_demo = $status5_demo;
    }

    public function getStatus6_delete() {
        return $this->status6_delete;
    }

    public function setStatus6_delete($status6_delete) {
        $this->status6_delete = $status6_delete;
    }

    public function getStatus6_apple() {
        return $this->status6_apple;
    }

    public function setStatus6_apple($status6_apple) {
        $this->status6_apple = $status6_apple;
    }

    public function getStatus6_demo() {
        return $this->status6_demo;
    }

    public function setStatus6_demo($status6_demo) {
        $this->status6_demo = $status6_demo;
    }

    public function getStatus7_delete() {
        return $this->status7_delete;
    }

    public function setStatus7_delete($status7_delete) {
        $this->status7_delete = $status7_delete;
    }

    public function getStatus7_apple() {
        return $this->status7_apple;
    }

    public function setStatus7_apple($status7_apple) {
        $this->status7_apple = $status7_apple;
    }

    public function getStatus7_demo() {
        return $this->status7_demo;
    }

    public function setStatus7_demo($status7_demo) {
        $this->status7_demo = $status7_demo;
    }
    
    public function getStatus4A_delete() {
        return $this->status4A_delete;
    }

    public function setStatus4A_delete($status4A_delete) {
        $this->status4A_delete = $status4A_delete;
    }

    public function getStatus4A_apple() {
        return $this->status4A_apple;
    }

    public function setStatus4A_apple($status4A_apple) {
        $this->status4A_apple = $status4A_apple;
    }

    public function getStatus4A_demo() {
        return $this->status4A_demo;
    }

    public function setStatus4A_demo($status4A_demo) {
        $this->status4A_demo = $status4A_demo;
    }

    public function getStatus4B_delete() {
        return $this->status4B_delete;
    }

    public function setStatus4B_delete($status4B_delete) {
        $this->status4B_delete = $status4B_delete;
    }

    public function getStatus4B_apple() {
        return $this->status4B_apple;
    }

    public function setStatus4B_apple($status4B_apple) {
        $this->status4B_apple = $status4B_apple;
    }

    public function getStatus4B_demo() {
        return $this->status4B_demo;
    }

    public function setStatus4B_demo($status4B_demo) {
        $this->status4B_demo = $status4B_demo;
    }

    public function getCancelReason() {
        return $this->cancelReason;
    }

    public function setCancelReason($cancelReason) {
        $this->cancelReason = $cancelReason;
    }


    public function getStatus4_delete() {
        return $this->status4_delete;
    }

    public function setStatus4_delete($status4_delete) {
        $this->status4_delete = $status4_delete;
    }

    public function getStatus4_apple() {
        return $this->status4_apple;
    }

    public function setStatus4_apple($status4_apple) {
        $this->status4_apple = $status4_apple;
    }

    public function getStatus4_demo() {
        return $this->status4_demo;
    }

    public function setStatus4_demo($status4_demo) {
        $this->status4_demo = $status4_demo;
    }

    public function getUserOrderStatusId() {
        return $this->userOrderStatusId;
    }

    public function setUserOrderStatusId($userOrderStatusId) {
        $this->userOrderStatusId = $userOrderStatusId;
    }
    
    public function getPayAmount() {
        return $this->payAmount;
    }

    public function setPayAmount($payAmount) {
        $this->payAmount = $payAmount;
    }
    
    public function getDiscount() {
        return $this->discount;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function getDemofile() {
        return $this->demofile;
    }

    public function setDemofile($demofile) {
        $this->demofile = $demofile;
    }

    public function getClientid() {
        return $this->clientid;
    }

    public function setClientid($clientid) {
        $this->clientid = $clientid;
    }
    
    public function getCleanFiles() {
        return $this->cleanFiles;
    }

    public function setCleanFiles($cleanFiles) {
        $this->cleanFiles = $cleanFiles;
    }

    public function getDemoFiles() {
        return $this->demoFiles;
    }

    public function setDemoFiles($demoFiles) {
        $this->demoFiles = $demoFiles;
    }

    public function getPdfFiles() {
        return $this->pdfFiles;
    }

    public function setPdfFiles($pdfFiles) {
        $this->pdfFiles = $pdfFiles;
    }
    
    public function getPreviousStatus() {
        return $this->previousStatus;
    }

    public function setPreviousStatus($previousStatus) {
        $this->previousStatus = $previousStatus;
    }

    public function getTimePending() {
        return $this->timePending;
    }

    public function setTimePending($timePending) {
        $this->timePending = $timePending;
    }

    public function getPromoType() {
        return $this->promoType;
    }

    public function setPromoType($promoType) {
        $this->promoType = $promoType;
    }
    
    public function getTotalCreditedBalance() {
        return $this->totalCreditedBalance;
    }

    public function setTotalCreditedBalance($totalCreditedBalance) {
        $this->totalCreditedBalance = $totalCreditedBalance;
    }  

    public function getTotalCreditedBalanceLeft() {
        return $this->totalCreditedBalanceLeft;
    }

    public function setTotalCreditedBalanceLeft($totalCreditedBalanceLeft) {
        $this->totalCreditedBalanceLeft = $totalCreditedBalanceLeft;
    }

    public function getIsCreditAvailableMode() {
        return $this->isCreditAvailableMode;
    }

    public function setIsCreditAvailableMode($isCreditAvailableMode) {
        $this->isCreditAvailableMode = $isCreditAvailableMode;
    }

    public function getPaymentEmail() {
        return $this->paymentEmail;
    }

    public function setPaymentEmail($paymentEmail) {
        $this->paymentEmail = $paymentEmail;
    }

    public function getAmountcharged() {
        return $this->amountcharged;
    }

    public function setAmountcharged($amountcharged) {
        $this->amountcharged = $amountcharged;
    }
    public function getPayflowAmount() {
        return $this->payflowAmount;
    }

    public function setPayflowAmount($payflowAmount) {
        $this->payflowAmount = $payflowAmount;
    }

    public function getInterval() {
        return $this->interval;
    }

    public function setInterval($interval) {
        $this->interval = $interval;
    }



}

?>
