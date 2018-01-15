<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'Profile.php';
include_once 'Order.php';
include_once 'UserOrder.php';
include_once 'Constants.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';

/**
 * Description of CustomersOrders
 *
 * @author Rishi
 */
class CustomersOrders {
    
    private $cancelledOrders;
    private $orders;
    private $promotionB_orders;
    
    private $order;
    
    private $connectionManager;
    private $queryManager;       
    
    function __construct() {
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());  
        
        $this->order = new Order();
        
    }
    
    function fetchCancelledOrders($loginid) {
        $flag;
        
        $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus = ". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED ." AND user_orders.id = user_mapping.orderid";
        
        if (!empty($loginid)){
            $sql = $sql . " AND user_mapping.loginid = $loginid";
        }
        
        $sql = $sql . " ORDER BY user_orders.id ASC";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $cancelledOrders = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $record = array(
                            'orderNo' => $row['orderid'].'-'.$row['loginid'],
                            'project' => $row['projectname'],
                            'credit' => '$'.$row['creditbalance'],
                            'clientid' => $row['loginid'],
                            'orderid'     =>  $row['orderid'],
                           );
                $cancelledOrders[$ctr] = $record;
                $ctr+=1;
            }
            $this->cancelledOrders = $cancelledOrders;
            $flag = TRUE;
        }
        else{
             $flag = FALSE;
        }
        return $flag;
    }
    
    function fetchOrders($loginid) {
        
        $flag;
        
        $sql = "SELECT user_orders.createdon, timeractivatedon, user_mapping.orderid, user_mapping.loginid, fname, lname, companyname, projectname, status, comments, userorderstatusid, timer, totalprice   
                FROM user_orders, user_profile, user_mapping LEFT OUTER JOIN user_promotions ON user_mapping.userpromotionid = user_promotions.iduser_promotions 
                WHERE user_mapping.profileid = user_profile.id AND user_orders.id = user_mapping.orderid AND  
                paymentstatus NOT IN (". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED .", ".Constants::ORDER_PAYMENT_STATUS_CANCELLED.", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.") 
                AND (user_promotions.promotype = 'A' OR user_promotions.promotype IS NULL ) ";
        
        if (!empty($loginid)){
            $sql = $sql . " AND user_profile.loginid = $loginid";
        }
        
        $sql = $sql . " ORDER BY user_orders.id ASC";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $orders = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                
                $sql = "SELECT * FROM user_orders_status WHERE iduser_orders_status = ".$row['userorderstatusid'];
                $result2 = $this->queryManager->query($sql);
                if ($result2){
                    $row2 = $this->queryManager->fetchSingleRow($result2);
                    if ($row2){
                        $statusArray = array(
                                    'status1_delete'    =>  (int)$row2['status1_delete'],
                                    'status1_apple'     =>  (int)$row2['status1_apple'],
                                    'status1_demo'      =>  (int)$row2['status1_demo'],
                                    'status2_delete'    =>  (int)$row2['status2_delete'],
                                    'status2_apple'     =>  (int)$row2['status2_apple'],
                                    'status2_demo'      =>  (int)$row2['status2_demo'],
                                    'status3_delete'    =>  (int)$row2['status3_delete'],
                                    'status3_apple'     =>  (int)$row2['status3_apple'],
                                    'status3_demo'      =>  (int)$row2['status3_demo'],
                                    'status4_delete'    =>  (int)$row2['status4_delete'],
                                    'status4_apple'     =>  (int)$row2['status4_apple'],
                                    'status4_demo'      =>  (int)$row2['status4_demo'],
                                    'status5_delete'    =>  (int)$row2['status5_delete'],
                                    'status5_apple'     =>  (int)$row2['status5_apple'],
                                    'status5_demo'      =>  (int)$row2['status5_demo'],
                                    'status6_delete'    =>  (int)$row2['status6_delete'],
                                    'status6_apple'     =>  (int)$row2['status6_apple'],
                                    'status6_demo'      =>  (int)$row2['status6_demo'],
                                    'status7_delete'    =>  (int)$row2['status7_delete'],
                                    'status7_apple'     =>  (int)$row2['status7_apple'],
                                    'status7_demo'      =>  (int)$row2['status7_demo']
                              );
                    }
                }
                $timePending = "";
                $timer = $row['timer'];
                $timerActivatedTimestampTemp = $row['timeractivatedon'];
                $timerActivatedTimestampTemp = strtotime($timerActivatedTimestampTemp);
                $interval = NULL;
                    
                if ($timer == 1){
                    
                    $timerActivatedTimestamp = new DateTime();
                    $timerActivatedTimestamp->setTimestamp($timerActivatedTimestampTemp);                    
                    $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                    $currentTimestamp = new DateTime();
                    $interval = $timerActivatedTimestamp->diff($currentTimestamp, true);
                    $timePending = $interval->format('%a D : %H:%I:%S');
                }
                $creationTimestampTemp = $row['createdon'];
                $creationTimestampTemp = strtotime($creationTimestampTemp);
                $date = date('m/d/Y', $creationTimestampTemp);
                
                $record = array(
                            'orderNo'           =>  $row['orderid'].'-'.$row['loginid'],
                            'creationdate'      =>  $date,
                            'clientid'          =>  $row['loginid'],
                            'fname'             =>  $row['fname'],
                            'lname'             =>  $row['lname'],
                            'companyname'       =>  $row['companyname'],                    
                            'project'           =>  $row['projectname'],
                            'timepending'       =>  $timePending,
                            'interval'          => $interval,
                            'status'            =>  $row['status'],
                            'comments'          =>  $row['comments'],
                            'orderid'           =>  $row['orderid'],
                            'ctr'               =>  $ctr,
                            'timer'             =>  $row['timer'],
                            'amount'             =>  $row['totalprice'],
                            'statusArray'       =>  $statusArray
                           );
                
                $orders[$ctr] = $record;
                $ctr+=1;
            }
            $this->orders = $orders;
            $flag = TRUE;
        }
        else{
             $flag = FALSE;
        }
        return $flag;
        
    }
    
    function fetchPromotionB_Orders($loginid) {
        
        $flag;
        
        $sql = "SELECT user_orders.createdon, timeractivatedon, user_mapping.orderid, user_mapping.loginid, fname, lname, companyname, projectname, status, comments, userorderstatusid, timer, totalprice   
                FROM user_orders, user_profile, user_mapping LEFT OUTER JOIN user_promotions ON user_mapping.userpromotionid = user_promotions.iduser_promotions 
                WHERE user_mapping.profileid = user_profile.id AND user_orders.id = user_mapping.orderid AND 
                paymentstatus NOT IN (". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED .", ".Constants::ORDER_PAYMENT_STATUS_CANCELLED.", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.") 
                AND (user_promotions.promotype = 'B' ) ";
        
        if (!empty($loginid)){
            $sql = $sql . " AND user_profile.loginid = $loginid";
        }
        
        $sql = $sql . " ORDER BY user_orders.id ASC";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $orders = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                
                $sql = "SELECT * FROM user_orders_promotion_b_status WHERE iduser_orders_promotion_b_status = ".$row['userorderstatusid'];
                $result2 = $this->queryManager->query($sql);
                if ($result2){
                    $row2 = $this->queryManager->fetchSingleRow($result2);
                    if ($row2){
                        $statusArray = array(
                                    'status1_delete'    =>  (int)$row2['status1_delete'],
                                    'status1_apple'     =>  (int)$row2['status1_apple'],
                                    'status1_demo'      =>  (int)$row2['status1_demo'],
                                    'status2_delete'    =>  (int)$row2['status2_delete'],
                                    'status2_apple'     =>  (int)$row2['status2_apple'],
                                    'status2_demo'      =>  (int)$row2['status2_demo'],
                                    'status3_delete'    =>  (int)$row2['status3_delete'],
                                    'status3_apple'     =>  (int)$row2['status3_apple'],
                                    'status3_demo'      =>  (int)$row2['status3_demo'],
                                    'status4A_delete'   =>  (int)$row2['status4A_delete'],
                                    'status4A_apple'    =>  (int)$row2['status4A_apple'],
                                    'status4A_demo'     =>  (int)$row2['status4A_demo'],
                                    'status4B_delete'   =>  (int)$row2['status4B_delete'],
                                    'status4B_apple'    =>  (int)$row2['status4B_apple'],
                                    'status4B_demo'     =>  (int)$row2['status4B_demo'],
                                    'status5_delete'   =>  (int)$row2['status5B_delete'],
                                    'status5_apple'    =>  (int)$row2['status5B_apple'],
                                    'status5_demo'     =>  (int)$row2['status5B_demo'],
                                    'status6_delete'   =>  (int)$row2['status6B_delete'],
                                    'status6_apple'    =>  (int)$row2['status6B_apple'],
                                    'status6_demo'     =>  (int)$row2['status6B_demo'],
                                    'status7_delete'   =>  (int)$row2['status7B_delete'],
                                    'status7_apple'    =>  (int)$row2['status7B_apple'],
                                    'status7_demo'     =>  (int)$row2['status7B_demo']
                              );
                    }
                }
                $timePending = "";
                $timer = $row['timer'];
                $timerActivatedTimestampTemp = $row['timeractivatedon'];
                $timerActivatedTimestampTemp = strtotime($timerActivatedTimestampTemp);
                $interval = NULL;
                    
                if ($timer == 1){
                    
                    $timerActivatedTimestamp = new DateTime();
                    $timerActivatedTimestamp->setTimestamp($timerActivatedTimestampTemp);                    
                    $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                    $currentTimestamp = new DateTime();
                    $interval = $timerActivatedTimestamp->diff($currentTimestamp, true);
                    $timePending = $interval->format('%a D : %H:%I:%S');
                }
                $creationTimestampTemp = $row['createdon'];
                $creationTimestampTemp = strtotime($creationTimestampTemp);
                $date = date('m/d/Y', $creationTimestampTemp);
                
                $record = array(
                            'orderNo'           =>  $row['orderid'].'-'.$row['loginid'],
                            'creationdate'      =>  $date,
                            'clientid'          =>  $row['loginid'],
                            'fname'             =>  $row['fname'],
                            'lname'             =>  $row['lname'],
                            'companyname'       =>  $row['companyname'],                    
                            'project'           =>  $row['projectname'],
                            'timepending'       =>  $timePending,
                            'interval'          =>  $interval,
                            'status'            =>  $row['status'],
                            'comments'          =>  $row['comments'],
                            'orderid'           =>  $row['orderid'],
                            'ctr'               =>  $ctr,
                            'timer'             =>  $row['timer'],
                            'amount'             =>  $row['totalprice'],
                            'statusArray'       =>  $statusArray
                           );
                
                $orders[$ctr] = $record;
                $ctr+=1;
            }
            $this->promotionB_orders = $orders;
            $flag = TRUE;
        }
        else{
             $flag = FALSE;
        }
        return $flag;
        
    }
    
    public function updateOrder(){
       $comments =  $this->order->getComments();
       $orderid = $this->order->getOrderNo();
       $timer = $this->order->getTimer();
       
       $status = $this->order->getStatus();
       
       $status1_delete = $this->order->getStatus1_delete();
       $status1_apple = $this->order->getStatus1_apple();
       $status1_demo = $this->order->getStatus1_demo();
       $status2_delete = $this->order->getStatus2_delete();
       $status2_apple = $this->order->getStatus2_apple();
       $status2_demo = $this->order->getStatus2_demo();
       $status3_delete = $this->order->getStatus3_delete();
       $status3_apple = $this->order->getStatus3_apple();
       $status3_demo = $this->order->getStatus3_demo();
       $status4_delete = $this->order->getStatus4_delete();
       $status4_apple = $this->order->getStatus4_apple();
       $status4_demo = $this->order->getStatus4_demo();
       $status5_delete = $this->order->getStatus5_delete();
       $status5_apple = $this->order->getStatus5_apple();
       $status5_demo = $this->order->getStatus5_demo();
       $status6_delete = $this->order->getStatus6_delete();
       $status6_apple = $this->order->getStatus6_apple();
       $status6_demo = $this->order->getStatus7_demo();
       $status7_delete = $this->order->getStatus7_delete();
       $status7_apple = $this->order->getStatus7_apple();
       $status7_demo = $this->order->getStatus7_demo();
       
       
       $sql = "UPDATE user_orders SET comments = '$comments', status = '$status', lastupdated = NOW(), ";
       
       //Start the timer and count down. Check if it has already started or not
       if ($status == Constants::ORDER_STATUS_READY_20DAYS && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = NOW() WHERE id= $orderid";
           $timer = 1;
       }
       else if ($status == Constants::ORDER_STATUS_READY_10DAYS  && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = SUBDATE(NOW(), 10) WHERE id= $orderid";
           $timer = 1;
       }
       else if ($status == Constants::ORDER_STATUS_READY_5DAYS  && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = SUBDATE(NOW(), 15) WHERE id= $orderid";
           $timer = 1;
       }
       else if ($status == Constants::ORDER_STATUS_READY_1DAY  && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = SUBDATE(NOW(), 19) WHERE id= $orderid";
           $timer = 1;
       }
       else{
           $sql = $sql . "timer = $timer WHERE id= $orderid";
       }
       $result = $this->queryManager->update($sql);
       
       if ($result){
           
           //Start the timer and count down. Check if it has already started or not
           //if (($status == Constants::ORDER_STATUS_READY_20DAYS || $status == Constants::ORDER_STATUS_READY_10DAYS || $status == Constants::ORDER_STATUS_READY_5DAYS || $status == Constants::ORDER_STATUS_READY_1DAY) && $status != $this->order->getPreviousStatus()){
           if (($status == Constants::ORDER_STATUS_READY_20DAYS || $status == Constants::ORDER_STATUS_READY_10DAYS || $status == Constants::ORDER_STATUS_READY_5DAYS || $status == Constants::ORDER_STATUS_READY_1DAY) && $timer == 1 ){
               $sql = "SELECT timeractivatedon from user_orders WHERE id= $orderid";
               $result2 = $this->queryManager->query($sql);
               if ($result){
                   $row = $this->queryManager->fetchSingleRow($result2);
                   if ($row){
                        $timerActivatedTimestampTemp = $row['timeractivatedon'];
                        $timerActivatedTimestampTemp = strtotime($timerActivatedTimestampTemp);
                        $timerActivatedTimestamp = new DateTime();
                        $timerActivatedTimestamp->setTimestamp($timerActivatedTimestampTemp);                    
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $currentTimestamp = new DateTime();
                        $interval = $timerActivatedTimestamp->diff($currentTimestamp, true);
                        $timePending = $interval->format('%a D : %H:%I:%S');
                        $this->order->setTimePending($timePending);
                        $this->order->setTimer(1);
                        $this->order->setInterval($interval);
                   }
               }
           }
           
           $sql = "UPDATE user_orders_status, user_mapping SET status1_delete = $status1_delete, status1_apple = $status1_apple, status1_demo = $status1_demo,
                  status2_delete = $status2_delete, status2_apple = $status2_apple, status2_demo = $status2_demo,
                  status3_delete = $status3_delete, status3_apple = $status3_apple, status3_demo = $status3_demo,
                  status4_delete = $status4_delete, status4_apple = $status4_apple, status4_demo = $status4_demo,
                  status5_delete = $status5_delete, status5_apple = $status5_apple, status5_demo = $status5_demo,
                  status6_delete = $status6_delete, status6_apple = $status6_apple, status6_demo = $status6_demo,
                  status7_delete = $status7_delete, status7_apple = $status7_apple, status7_demo = $status7_demo,
                  user_orders_status.lastupdated = NOW()    
                  WHERE user_mapping.userorderstatusid = user_orders_status.iduser_orders_status AND user_mapping.orderid = $orderid ";
           
           $result = $this->queryManager->update($sql);
           
           if ($result){
               if ($status != $this->order->getPreviousStatus()){
                    //Send Mail
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
                             'noofemails' => $row['noofrecords']
                             );

                        $emailNotification = new EmailNotification();
                        $mail = $emailNotification->sendStatusEmail($mailInfo, $status);
                    }
                }

               return TRUE;
           }
           else{
                return FALSE;
           }
       }
       else{
           return FALSE;
       }
    }
    
    public function updatePromotionB_Order(){
       $comments =  $this->order->getComments();
       $orderid = $this->order->getOrderNo();
       $timer = $this->order->getTimer();
       
       $status = $this->order->getStatus();
       
       $status1_delete = $this->order->getStatus1_delete();
       $status1_apple = $this->order->getStatus1_apple();
       $status1_demo = $this->order->getStatus1_demo();
       $status2_delete = $this->order->getStatus2_delete();
       $status2_apple = $this->order->getStatus2_apple();
       $status2_demo = $this->order->getStatus2_demo();
       $status3_delete = $this->order->getStatus3_delete();
       $status3_apple = $this->order->getStatus3_apple();
       $status3_demo = $this->order->getStatus3_demo();
       $status4A_delete = $this->order->getStatus4A_delete();
       $status4A_apple = $this->order->getStatus4A_apple();
       $status4A_demo = $this->order->getStatus4A_demo();
       $status4B_delete = $this->order->getStatus4B_delete();
       $status4B_apple = $this->order->getStatus4B_apple();
       $status4B_demo = $this->order->getStatus4B_demo();
       $status5_delete = $this->order->getStatus5_delete();
       $status5_apple = $this->order->getStatus5_apple();
       $status5_demo = $this->order->getStatus5_demo();
       $status6_delete = $this->order->getStatus6_delete();
       $status6_apple = $this->order->getStatus6_apple();
       $status6_demo = $this->order->getStatus7_demo();
       $status7_delete = $this->order->getStatus7_delete();
       $status7_apple = $this->order->getStatus7_apple();
       $status7_demo = $this->order->getStatus7_demo();
       
       
       $sql = "UPDATE user_orders SET comments = '$comments', status = '$status', lastupdated = NOW(), ";
       
       //Start the timer and count down. Check if it has already started or not
       if ( ($status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID) && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = NOW() WHERE id= $orderid";
           $timer = 1;
       }
       else if ( ($status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS) && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = SUBDATE(NOW(), 10) WHERE id= $orderid";
           $timer = 1;
       }
       else if ( ($status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS) && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = SUBDATE(NOW(), 15) WHERE id= $orderid";
           $timer = 1;
       }
       else if ( ($status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY) && $status != $this->order->getPreviousStatus()){
           $sql = $sql . "timer = 1, timeractivatedon = SUBDATE(NOW(), 19) WHERE id= $orderid";
           $timer = 1;
       }
       else{
           $sql = $sql . "timer = $timer WHERE id= $orderid";
       }
       $result = $this->queryManager->update($sql);
       
       if ($result){
           //Start the timer and count down. Check if it has already started or not
           //if (($status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY) && $status != $this->order->getPreviousStatus()){
           if (($status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS || $status == Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY) && $timer == 1){
               $sql = "SELECT timeractivatedon from user_orders WHERE id= $orderid";
               $result2 = $this->queryManager->query($sql);
               if ($result){
                   $row = $this->queryManager->fetchSingleRow($result2);
                   if ($row){
                        $timerActivatedTimestampTemp = $row['timeractivatedon'];
                        $timerActivatedTimestampTemp = strtotime($timerActivatedTimestampTemp);
                        $timerActivatedTimestamp = new DateTime();
                        $timerActivatedTimestamp->setTimestamp($timerActivatedTimestampTemp);                    
                        $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                        $currentTimestamp = new DateTime();
                        $interval = $timerActivatedTimestamp->diff($currentTimestamp, true);
                        $timePending = $interval->format('%a D : %H:%I:%S');
                        $this->order->setTimePending($timePending);
                        $this->order->setTimer(1);
                        $this->order->setInterval($interval);
                   }
               }
           }
           
           $sql = "UPDATE user_orders_promotion_b_status, user_mapping SET status1_delete = $status1_delete, status1_apple = $status1_apple, status1_demo = $status1_demo,
                  status2_delete = $status2_delete, status2_apple = $status2_apple, status2_demo = $status2_demo,
                  status3_delete = $status3_delete, status3_apple = $status3_apple, status3_demo = $status3_demo,
                  status4A_delete = $status4A_delete, status4A_apple = $status4A_apple, status4A_demo = $status4A_demo,
                  status4B_delete = $status4B_delete, status4B_apple = $status4B_apple, status4B_demo = $status4B_demo,
                  status5B_delete = $status5_delete, status5B_apple = $status5_apple, status5B_demo = $status5_demo,
                  status6B_delete = $status6_delete, status6B_apple = $status6_apple, status6B_demo = $status6_demo,
                  status7B_delete = $status7_delete, status7B_apple = $status7_apple, status7B_demo = $status7_demo,
                  user_orders_promotion_b_status.lastupdated = NOW()  
                  WHERE user_mapping.userorderstatusid = user_orders_promotion_b_status.iduser_orders_promotion_b_status AND user_mapping.orderid = $orderid ";
           
           $result = $this->queryManager->update($sql);
           
           if ($result){
               if ($status != $this->order->getPreviousStatus()){
                    //Send Mail
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
               return TRUE;
           }
           else{
                return FALSE;
           }
       }
       else{
           return FALSE;
       }
    }
    
    public function updateCancelledOrder(){
       $cancelreason =  $this->order->getCancelReason();
       $orderid = $this->order->getOrderNo();
       $amount = $this->order->getTotalPrice();
       
       $sql = "UPDATE user_orders SET cancelreason = '$cancelreason', creditbalance = $amount, cancelledon = NOW(), paymentstatus = ". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED .", lastupdated = NOW() WHERE id = $orderid";
       $result = $this->queryManager->update($sql);
       
       if ($result){
           //Send mail
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
                     'creditamount' => $amount,
                     'cancelreason' => $cancelreason
                     );

                $emailNotification = new EmailNotification();
                $mail = $emailNotification->sendCancelOrderEmail($mailInfo);
            }
           return TRUE;
       }
       else{
           return FALSE;
       }
    }
    
    public function deleteUserOrdersPermanently(){
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());  
        
       $flag;
        
       $orderid = $this->order->getOrderNo();
       $clientid = $this->order->getClientid();
       
       //$sql = "UPDATE user_orders SET paymentstatus = ". Constants::ORDER_PAYMENT_STATUS_CANCELLED .", lastupdated = NOW() WHERE id = $orderid";
       //$result = $this->queryManager->update($sql);
       
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
    
    public function deleteUserOrders(){
        $flag = -1;
        $status = '';
        
       $orderid = $this->order->getOrderNo();
       $clientid = $this->order->getClientid();
       
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
                $flag = 3;
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
    
    public function updateCustomerFolder($orderid, $clientid, $type, $selectedOption){
        $flag;
        
        $folderName = $orderid . '-' . $clientid;
        
        $customerDirLocation = Config::getCustomerFolder_location().$folderName;
        
        if (file_exists($customerDirLocation)){
            switch ($selectedOption) {
                case "1":   
                    if ($type == 'LOAD'){
                        $currentdate = date('Y-m-d');

                        $fileName = Config::$customerOrders_cleanFile_name.$currentdate.'_'.$_FILES['filename']['name'];

                        $filelocation = $customerDirLocation.DIRECTORY_SEPARATOR.$fileName;

                        if (move_uploaded_file($_FILES['filename']['tmp_name'], $filelocation)){
                            $flag = 2;
                        }
                        else{
                            $flag = 1;
                        }
                    }
                    else{
                        $deleteFiles = $customerDirLocation.DIRECTORY_SEPARATOR.Config::$customerOrders_cleanFile_name.'*.*';
                        foreach (glob($deleteFiles) as $filename){
                            unlink($filename);
                        }
                         $flag = 2;
                    }

                    break;

                case "2":
                    if ($type == 'LOAD'){
                        $currentdate = date('Y-m-d');

                        $fileName = Config::$customerOrders_demoFile_name.$currentdate.'_'.$_FILES['filename']['name'];

                        $filelocation = $customerDirLocation.DIRECTORY_SEPARATOR.$fileName;

                        if (move_uploaded_file($_FILES['filename']['tmp_name'], $filelocation)){
                            $flag = 2;
                        }
                        else{
                            $flag = 1;
                        }
                    }
                    else{
                        $deleteFiles = $customerDirLocation.DIRECTORY_SEPARATOR.Config::$customerOrders_demoFile_name.'*.*';
                        foreach (glob($deleteFiles) as $filename){
                            unlink($filename);
                        }
                        $flag = 2;
                    }

                    break;

                case "3":
                    if ($type == 'LOAD'){
                        $currentdate = date('Y-m-d');

                        $fileName = Config::$customerOrders_pdfFile_name.$currentdate.'_'.$_FILES['filename']['name'];

                        $filelocation = $customerDirLocation.DIRECTORY_SEPARATOR.$fileName;

                        if (move_uploaded_file($_FILES['filename']['tmp_name'], $filelocation)){
                            $flag = 2;
                        }
                        else{
                            $flag = 1;
                        }
                    }
                    else{
                        $deleteFiles = $customerDirLocation.DIRECTORY_SEPARATOR.Config::$customerOrders_pdfFile_name.'*.*';
                        foreach (glob($deleteFiles) as $filename){
                            unlink($filename);
                        }
                        $flag = 2;
                    }

                    break;

                default:
                    $flag = 3;
            }
        }
        else{
            $flag = 0;
        }
        
        return $flag;
    }
    
    public function getCancelledOrders() {
        return $this->cancelledOrders;
    }

    public function setCancelledOrders($cancelledOrders) {
        $this->cancelledOrders = $cancelledOrders;
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setOrders($orders) {
        $this->orders = $orders;
    }

    public function getPromotionB_orders() {
        return $this->promotionB_orders;
    }

    public function setPromotionB_orders($promotionB_orders) {
        $this->promotionB_orders = $promotionB_orders;
    }

    public function getQueryManager() {
        return $this->queryManager;
    }

    public function setQueryManager($queryManager) {
        $this->queryManager = $queryManager;
    }
    
    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }




   
}

?>
