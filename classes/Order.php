<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'Profile.php';
include_once 'Order.php';
include_once 'UserOrder.php';
include_once 'Constants.php';
include_once 'PDFReportGenerator.php';
include_once 'EmailNotification.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
require_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';


/**
 * Description of Order
 *
 * @author Rishi
 */
class Order {
    
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
    
    private $totalCreditedBalanceUnformatted;
    
    private $userOrderStatusId;
    
    private $isPromotionApplied;
    
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
    
    function uploadDataFileToFTP(Profile $profile) {
        $flag;
        
        if (!$_FILES['filename']['error']) {
           $allowedExts = array("xls", "xlsx", "csv", "txt");
           $expArr = explode(".", $_FILES['filename']['name']);
           $extension = end($expArr);
           
           if (in_array($extension, $allowedExts)) {
               $this->fileName = $this->orderNo . '-' . $profile->getClientId();
               $filelocation = Config::getCustomerDataBank_location();
               
               $filelocation = $filelocation.$this->fileName.'.'.$extension;
                   
                if (move_uploaded_file($_FILES['filename']['tmp_name'], $filelocation)){
                    //Create customer folder
                    $dirlocation = Config::getCustomerFolder_location().$this->fileName;
                    $dir = mkdir($dirlocation);
                     if ($dir){
                         $flag = TRUE;
                     }
                     else{
                         $this->message = "Failed to create directory. Please Contact Us.";
                         $flag = FALSE;
                     }                       
                }
                else{
                    $flag = FALSE;
                }
           }
           else{
               $this->message = "File type not supported.";
               $flag = FALSE;
           }
        }
        else{
            $this->message = $_FILES['filename']['error'];
            $flag = FALSE;
        }
        
        return $flag;
    }
    
    /*function uploadDataFileToFTP(Profile $profile) {
        $flag = TRUE;
        
        if (!$_FILES['filename']['error']) {
           $allowedExts = array("xls", "xlsx", "csv", "txt");
           $expArr = explode(".", $_FILES['filename']['name']);
           $extension = end($expArr);
           
           if (in_array($extension, $allowedExts)) {
               $this->fileName = $this->orderNo . '-' . $profile->getClientId();
               $filelocation = Config::getCustomerDataBank_location();               
               $this->fileName = $this->fileName.'.'.$extension;
               
               // set up basic connection
               $FTPconnection = ftp_connect(Config::$ftp_host); 
               
               if ($FTPconnection){
                   //login with username and password
                    $FTPLogin = ftp_login($FTPconnection, Config::$ftp_user, Config::$ftp_pwd); 

                    if ((!$FTPconnection) || (!$FTPLogin)) { 
                        $this->message = "Failed to login to server. Please Contact Us with this message.";
                        $flag = FALSE;
                    }
                    else{
                        ftp_pasv($FTPconnection,TRUE);
                        $curdir = ftp_pwd($FTPconnection);
                        $dir = ftp_chdir($FTPconnection, $curdir);
                        if ($dir){
                            $FTPupload = ftp_put($FTPconnection, $this->fileName, $_FILES['filename']['tmp_name'], FTP_BINARY); 
                            if (!$FTPupload){
                                $this->message = "Failed to upload file to server. Please Contact Us with this message.";
                                $flag = FALSE;
                            }
                        }
                        else{
                            $this->message = "Could not found the data bank on server. Please Contact Us with this message.";
                            $flag = FALSE;
                        }
                        ftp_close($FTPconnection); 
                    }
                   
               }
               else{
                   $this->message = "Failed to connect to server. Please Contact Us with this message.";
                   $flag = FALSE;
               }
               
               //Create customer folder on server
                $dirlocation = Config::getCustomerFolder_location().$this->orderNo . '-' . $profile->getClientId();
                $dir = mkdir($dirlocation);
                if (!$dir){
                    $this->message = "Failed to create directory. Please Contact Us.";
                    $flag = FALSE;
                }
           }
           else{
               $this->message = "File type not supported.";
               $flag = FALSE;
           }
        }
        else{
            $this->message = $_FILES['filename']['error'];
            $flag = FALSE;
        }
        
        return $flag;
    }*/
    
    function saveOrder(Profile $profile) {
        $flag;
        
        $sql = "INSERT INTO user_orders (projectname, noofrecords, totalprice, processingtime, paymentstatus, status, payamount, createdon , lastupdated ) 
                VALUES ('$this->projectName', $this->noOfRecords, $this->totalPrice, $this->processingTime,". Constants::ORDER_PAYMENT_STATUS_PENDING .",'" . Constants::ORDER_STATUS_NEW . "', $this->totalPrice, NOW(), NOW())";
        
        $result = $this->queryManager->queryInsertAndGetId($sql);
        if ($result){
            $this->orderNo = $result;
            
            $this->setClientid($profile->getLoginId());
            
            $sql = "INSERT INTO user_orders_status (createdon, lastupdated) VALUES (NOW(), NOW())";
            $result = $this->queryManager->queryInsertAndGetId($sql);
            
            if ($result){
                $this->setUserOrderStatusId($result);
                
                $userOrder = new UserOrder();
                $userOrder->setLoginId($profile->getLoginId());
                $userOrder->setProfileId($profile->getProfileId());
                $userOrder->setOrderId($this->orderNo);
                $userOrder->setUserOrderStatusId($this->userOrderStatusId);
                 
                $result = $userOrder->saveUserOrder();
                if ($result){
                    $this->payAmount = $this->totalPrice;
                    $this->payflowAmount = $this->payAmount ;
                    
                    //Sum up the credited balance in user account
                    $sql = "SELECT sum(creditbalance) AS sum FROM user_orders, user_mapping WHERE user_orders.id = user_mapping.orderid AND loginid = ".$profile->getLoginId()." AND paymentstatus = ".Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED;
                    print $sql;
                    $result = $this->queryManager->query($sql);
                    if ($result){
                        $row = $this->queryManager->fetchSingleRow($result);
                        $this->totalCreditedBalance = number_format($row['sum'], 2);
                        $this->totalCreditedBalanceUnformatted = $row['sum'];
                        $this->totalCreditedBalanceLeft = number_format(($row['sum'] - $this->payAmount), 2);
                    }
                    //$this->totalPrice = number_format($this->totalPrice, 2);
                    $flag = TRUE;
                }
                else{
                    $flag = FALSE;
                }
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
    
    public function applyPromotion(){
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());  
        
        $flag;
        $sql = "";
        $promo_detail = $_SESSION['userOrder_promodetail'];
        $promotype = "";
        
        $promoADetail = $promo_detail['promoADetail'];
        $promoBDetail = $promo_detail['promoBDetail'];
        
        if (!empty($promoADetail) && $_SESSION['pcode'] == $promoADetail['promocode']){
            $promotype = "A";
            $promo_detail = $promoADetail;
        }
        else if (!empty($promoBDetail) && $_SESSION['pcode'] == $promoBDetail['promocode']){
            $promotype = "B";
            $promo_detail = $promoBDetail;
        }
        //$promotype = $promo_detail['promotype'];        
        
        $discountPercent = $promo_detail['discount_or_demo'];
        $this->setPromoType($promotype);
        
        if ($promotype == 'A'){
            $payamount = $this->totalPrice - (($this->totalPrice * $discountPercent ) / 100);
                        
            $this->totalCreditedBalanceLeft = number_format($this->totalCreditedBalanceUnformatted - $payamount, 2);
            
            $payamount = number_format($payamount, 2);
            $this->payAmount = $payamount;
            $this->payflowAmount = $this->payAmount;
            
            $sql = "UPDATE user_orders SET payamount = $this->payAmount, promotionapplied = 1, discount = $discountPercent, lastupdated = NOW() WHERE id = $this->orderNo";
        }
        else{
            $sql = "UPDATE user_orders SET status = '".Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAYMENT_PENDING ."', demofile = $discountPercent, promotionapplied = 1, lastupdated = NOW() WHERE id = $this->orderNo";
        }
        
        $result = $this->queryManager->update($sql);
        if ($result){
            
            $promo_detail['promoApplied'] = "1";
            $_SESSION['userOrder_promodetail'] = $promo_detail;
            
            $sql = "INSERT INTO user_promotions (promotype, promocode, validfrom, validtill, unitrangefrom, unitrangeto, discount_or_demo, createdon) 
                    VALUES ('".$promo_detail['promotype']."','". $promo_detail['promocode']."','".$promo_detail['validfrom']."','".$promo_detail['validtill']."',".$promo_detail['unitrangefrom'].",".$promo_detail['unitrangetill'].",".$promo_detail['discount_or_demo'].",NOW() )";
            
            $userpromotionsid = $this->queryManager->queryInsertAndGetId($sql);
            if ($userpromotionsid){
                if ($promotype == 'B'){
                    $sql = "DELETE FROM user_orders_status WHERE iduser_orders_status = $this->userOrderStatusId";
                    $delete = $this->queryManager->query($sql);
                    
                    $sql = "INSERT INTO user_orders_promotion_b_status (createdon, lastupdated) VALUES (NOW(), NOW())";
                    $user_orders_promotion_b_id = $this->queryManager->queryInsertAndGetId($sql);
                    if ($user_orders_promotion_b_id){
                        $this->setUserOrderStatusId($user_orders_promotion_b_id);
                    }
                    
                    //Send Mail
                    $sql = "SELECT * FROM user_profile, user_orders, user_mapping WHERE user_orders.id = user_mapping.orderid AND user_profile.id = user_mapping.profileid AND orderid = $this->orderNo";
                    $result1 = $this->queryManager->query($sql);
                    if ($result1){
                        $row = $this->queryManager->fetchSingleRow($result1);

                        $mailInfo = array(
                             'fname' => $row['fname'],
                             'lname' => $row['lname'],
                             'email' => $row['email'],
                             'orderno' => $this->orderNo,
                             'project' => $row['projectname'],
                             );

                        $emailNotification = new EmailNotification();
                        //$mail = $emailNotification->sendStatusEmail($mailInfo, Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAYMENT_PENDING);
                    }
                }
                
                $sql = "UPDATE user_mapping SET userorderstatusid = $this->userOrderStatusId, userpromotionid = $userpromotionsid, lastupdated = NOW() WHERE orderid = $this->orderNo";
                $update = $this->queryManager->update($sql);
                if ($update){
                    $flag = TRUE;
                }
                else{
                    $flag = FALSE;
                }
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
    
    function fetchOrderCancelled($email) {
        $flag;
        
        $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus = ". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED ." AND email = '$email' AND user_orders.id = user_mapping.orderid";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $cancelledProjects = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $record = array(
                            'orderNo' => $row['orderid'].'-'.$row['loginid'],
                            'project' => $row['projectname'],
                            'credit' => '$'.$row['creditbalance']
                           );
                $cancelledProjects[$ctr] = $record;
                $ctr+=1;
            }
            $this->cancelledProjects = $cancelledProjects;
            $flag = TRUE;
        }
        else{
             $flag = FALSE;
        }
        return $flag;
    }
    
    function fetchOrderAll($email) {
        $flag;
        
        $sql = "SELECT paymentstatus,orderid,loginid,projectname,status,timer,timeractivatedon, promotionapplied, userpromotionid, userorderstatusid  FROM user_orders, user_mapping WHERE paymentstatus IN (" . Constants::ORDER_PAYMENT_STATUS_PAID .",". Constants::ORDER_PAYMENT_STATUS_PENDING.") AND email = '$email' AND user_orders.id = user_mapping.orderid";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $allProjects = array();
            $ctr = 1;
            
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                
                //If promotion applied
                $isPromtionApplied = $row['promotionapplied'];
                $promotype = "";
                
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
                
                //User order status - Check the indicator for Delete, Demo & apple files
                $showApple = 0;
                $showDemo = 0;
                $showDelete = 0;
                $orderStatus = $row['status'];
                
                $userOrderStatusId = $row['userorderstatusid'];
                if (!empty($promotype) && $promotype == 'B'){
                    $sql = "SELECT * FROM user_orders_promotion_b_status WHERE iduser_orders_promotion_b_status = $userOrderStatusId";
                    $result3 = $this->queryManager->query($sql);
                    if ($result3){
                        $row3 = $this->queryManager->fetchSingleRow($result3);
                        if ($row3){
                            $statusArray = array(
                                    'status1_delete'    =>  (int)$row3['status1_delete'],
                                    'status1_apple'     =>  (int)$row3['status1_apple'],
                                    'status1_demo'      =>  (int)$row3['status1_demo'],
                                    'status2_delete'    =>  (int)$row3['status2_delete'],
                                    'status2_apple'     =>  (int)$row3['status2_apple'],
                                    'status2_demo'      =>  (int)$row3['status2_demo'],
                                    'status3_delete'    =>  (int)$row3['status3_delete'],
                                    'status3_apple'     =>  (int)$row3['status3_apple'],
                                    'status3_demo'      =>  (int)$row3['status3_demo'],
                                    'status4A_delete'   =>  (int)$row3['status4A_delete'],
                                    'status4A_apple'    =>  (int)$row3['status4A_apple'],
                                    'status4A_demo'     =>  (int)$row3['status4A_demo'],
                                    'status4B_delete'   =>  (int)$row3['status4B_delete'],
                                    'status4B_apple'    =>  (int)$row3['status4B_apple'],
                                    'status4B_demo'     =>  (int)$row3['status4B_demo'],
                                    'status5_delete'   =>  (int)$row3['status5B_delete'],
                                    'status5_apple'    =>  (int)$row3['status5B_apple'],
                                    'status5_demo'     =>  (int)$row3['status5B_demo'],
                                    'status6_delete'   =>  (int)$row3['status6B_delete'],
                                    'status6_apple'    =>  (int)$row3['status6B_apple'],
                                    'status6_demo'     =>  (int)$row3['status6B_demo'],
                                    'status7_delete'   =>  (int)$row3['status7B_delete'],
                                    'status7_apple'    =>  (int)$row3['status7B_apple'],
                                    'status7_demo'     =>  (int)$row3['status7B_demo']
                              );
                            
                              switch ($orderStatus) {
                                  case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAYMENT_PENDING:
                                      //Delete indicator
                                      if ($statusArray['status1_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status1_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status1_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_APPROVING:
                                      //Delete indicator
                                      if ($statusArray['status2_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status2_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status2_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROCESSING:
                                      //Delete indicator
                                      if ($statusArray['status3_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status3_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status3_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_20DAYS:
                                      //Delete indicator
                                      if ($statusArray['status4A_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status4A_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status4A_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID:
                                      //Delete indicator
                                      if ($statusArray['status4B_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status4B_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status4B_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_10DAYS:
                                      //Delete indicator
                                      if ($statusArray['status5_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status5_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status5_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_5DAYS:
                                      //Delete indicator
                                      if ($statusArray['status6_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status6_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status6_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_READY_1DAY:
                                      //Delete indicator
                                      if ($statusArray['status7_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status7_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status7_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;

                                  default:
                                      break;
                              }                           
                            
                        }
                    }
                }
                else{
                    $sql = "SELECT * FROM user_orders_status WHERE iduser_orders_status = $userOrderStatusId";
                    $result3 = $this->queryManager->query($sql);
                    if ($result3){
                        $row3 = $this->queryManager->fetchSingleRow($result3);
                        if ($row3){
                            $statusArray = array(
                                    'status1_delete'    =>  (int)$row3['status1_delete'],
                                    'status1_apple'     =>  (int)$row3['status1_apple'],
                                    'status1_demo'      =>  (int)$row3['status1_demo'],
                                    'status2_delete'    =>  (int)$row3['status2_delete'],
                                    'status2_apple'     =>  (int)$row3['status2_apple'],
                                    'status2_demo'      =>  (int)$row3['status2_demo'],
                                    'status3_delete'    =>  (int)$row3['status3_delete'],
                                    'status3_apple'     =>  (int)$row3['status3_apple'],
                                    'status3_demo'      =>  (int)$row3['status3_demo'],
                                    'status4_delete'    =>  (int)$row3['status4_delete'],
                                    'status4_apple'     =>  (int)$row3['status4_apple'],
                                    'status4_demo'      =>  (int)$row3['status4_demo'],
                                    'status5_delete'    =>  (int)$row3['status5_delete'],
                                    'status5_apple'     =>  (int)$row3['status5_apple'],
                                    'status5_demo'      =>  (int)$row3['status5_demo'],
                                    'status6_delete'    =>  (int)$row3['status6_delete'],
                                    'status6_apple'     =>  (int)$row3['status6_apple'],
                                    'status6_demo'      =>  (int)$row3['status6_demo'],
                                    'status7_delete'    =>  (int)$row3['status7_delete'],
                                    'status7_apple'     =>  (int)$row3['status7_apple'],
                                    'status7_demo'      =>  (int)$row3['status7_demo']
                              );
                            
                            switch ($orderStatus) {
                                  case Constants::ORDER_STATUS_PLACED:
                                      //Delete indicator
                                      if ($statusArray['status1_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status1_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status1_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_APPROVING:
                                      //Delete indicator
                                      if ($statusArray['status2_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status2_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status2_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_PROCESSING:
                                      //Delete indicator
                                      if ($statusArray['status3_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status3_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status3_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_READY_20DAYS:
                                      //Delete indicator
                                      if ($statusArray['status4_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status4_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status4_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_READY_10DAYS:
                                      //Delete indicator
                                      if ($statusArray['status5_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status5_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status5_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_READY_5DAYS:
                                      //Delete indicator
                                      if ($statusArray['status6_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status6_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status6_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;
                                      
                                  case Constants::ORDER_STATUS_READY_1DAY:
                                      //Delete indicator
                                      if ($statusArray['status7_delete'] == 1){
                                          $showDelete = 1;
                                      }
                                      //Apple indicator
                                      if ($statusArray['status7_apple'] == 1){
                                          $showApple = 1;
                                      }
                                      //Demo indicator
                                      if ($statusArray['status7_demo'] == 1){
                                          $showDemo = 1;
                                      }
                                      
                                      break;

                                  default:
                                      break;
                              }  
                        }
                    }
                }
                
                //Payment Column
                $payment = $row['paymentstatus'];
                if ($payment == Constants::ORDER_PAYMENT_STATUS_PAID){
                    $payment = Constants::ORDER_PAYMENT_STATUS_PAID_STRING;
                }
                else if ($payment == Constants::ORDER_PAYMENT_STATUS_PENDING){
                    $payment = Constants::ORDER_PAYMENT_STATUS_PENDING_STRING;
                }
                
                //Timer column
                $timePending = "";
                $timer = $row['timer'];
                $interval = NULL;
                if ($timer == 1){
                    $timerActivatedTimestampTemp = $row['timeractivatedon'];
                    $timerActivatedTimestampTemp = strtotime($timerActivatedTimestampTemp);
                    $timerActivatedTimestamp = new DateTime();
                    $timerActivatedTimestamp->setTimestamp($timerActivatedTimestampTemp);                    
                    $timerActivatedTimestamp = $timerActivatedTimestamp->add(new DateInterval('P20D'));
                    $currentTimestamp = new DateTime();
                    $interval = $timerActivatedTimestamp->diff($currentTimestamp, true);
                    $timePending = $interval->format('%a D : %H:%I:%S');
                }
                
                //All consolidated columns
                $record = array(
                            'orderNo'               => $row['orderid'].'-'.$row['loginid'],
                            'project'               => $row['projectname'],
                            'status'                => $row['status'],
                            'timepending'           => $timePending,
                            'interval'              => $interval,
                            'paymentstatusString'   => $payment,
                            'orderid'               => $row['orderid'],
                            'clientid'              => $row['loginid'],
                            'timer'             =>  $row['timer'],
                            'showApple'             => $showApple,
                            'showDemo'              => $showDemo,
                            'showDelete'            => $showDelete
                           );
          
                
                $allProjects[$ctr] = $record;
                $ctr+=1;
            }
            $this->allProjects = $allProjects;
            $flag = TRUE;
        }
        else{
             $flag = FALSE;
        }
        return $flag;
    }
    
    public function fetchSingleOrder(){
        $flag;
        
        $sql = "SELECT * FROM user_orders WHERE id = $this->orderNo";
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $row = $this->queryManager->fetchSingleRow($result);
            if ($row){
                $this->setProjectName($row['projectname']);
                $this->setNoOfRecords($row['noofrecords']);
                $this->setTotalPrice($row['totalprice']);
                $this->setPayAmount($row['payamount']);
                $this->setPayflowAmount($row['payamount']);
                $this->setProcessingTime($row['processingtime']);
                $this->setStatus($row['status']);
                
                $isPromoApplied = $row['promotionapplied'];
                $this->setIsPromotionApplied($isPromoApplied);
                if ($isPromoApplied && $isPromoApplied == 1){
                    $sql = "SELECT * FROM user_promotions, user_mapping WHERE user_mapping.userpromotionid = user_promotions.iduser_promotions AND orderid = $this->orderNo";
                    $result2 = $this->queryManager->query($sql);
                    $row2 = $this->queryManager->fetchSingleRow($result2);
                    
                    if ($row2){
                        $this->setPromoCode($row2['promocode']);
                        $this->setPromoType($row2['promotype']);
                    }
                }
                
                //Sum up the credited balance in user account
                $sql = "SELECT sum(creditbalance) AS sum FROM user_orders, user_mapping WHERE user_orders.id = user_mapping.orderid AND paymentstatus = ".Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED." AND email = '".$_SESSION['email']."'";
                $result = $this->queryManager->query($sql);
                if ($result){
                    $row = $this->queryManager->fetchSingleRow($result);
                    $this->totalCreditedBalance = number_format($row['sum'], 2);
                    $this->totalCreditedBalanceLeft = number_format(($row['sum'] - $this->payAmount), 2);
                }
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
    
    public function downloadUserDataFiles(){
        $cleanFiles = array();
        $demoFiles = array();
        $pdfFiles = array();
        
        $folderName = $this->orderNo . '-' . $this->clientid;
        
        $customerDirLocation = Config::getCustomerFolder_location().$folderName;
        
        if (file_exists($customerDirLocation)){
            
            $files = $customerDirLocation.DIRECTORY_SEPARATOR.'*.*';
            foreach (glob($files) as $filename){
                $name = basename($filename);
                
                $pos = strpos($name, Config::$customerOrders_cleanFile_name);                
                if ($pos === 0){
                    array_push($cleanFiles, Config::getCustomerFolder_downloadableLocation().$folderName.DIRECTORY_SEPARATOR.$name);
                    continue;
                }
                
                $pos = strpos($name, Config::$customerOrders_demoFile_name);                
                if ($pos === 0){
                    array_push($demoFiles, Config::getCustomerFolder_downloadableLocation().$folderName.DIRECTORY_SEPARATOR.$name);
                    continue;
                }
                
                $pos = strpos($name, Config::$customerOrders_pdfFile_name);                
                if ($pos === 0){
                    array_push($pdfFiles, Config::getCustomerFolder_downloadableLocation().$folderName.DIRECTORY_SEPARATOR.$name);
                    continue;
                }
                
                $pos = strpos($name, Config::$customerOrders_transactionFile_name);                
                if ($pos === 0){
                    array_push($pdfFiles, Config::getCustomerFolder_downloadableLocation().$folderName.DIRECTORY_SEPARATOR.$name);
                    continue;
                }
            }
            
            $this->setCleanFiles($cleanFiles);
            $this->setDemoFiles($demoFiles);
            $this->setPdfFiles($pdfFiles);
            
        }
        else{
            $this->message = 'Folder not found for your order. Please contact Us.';
        }
        
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
    
    function updatePaymentStatusAndGenerateReceipt($updateStatus){
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());  
        
        $flag = 0;
        $paymentstatus = Constants::ORDER_PAYMENT_STATUS_PAID;
        $status = '';
    
        if (!empty($this->promoType) && $this->promoType == 'B'){
            $status = Constants::ORDER_STATUS_PROMOTION_B_ACCEPTED_PAID;
        }
        else{
            $status = Constants::ORDER_STATUS_PLACED;
        }
        
        //Update the status
        if ($updateStatus){
            $sql = '';
            if (!empty($this->promoType) && $this->promoType == 'B'){
                $sql = "UPDATE user_orders SET paymentstatus = $paymentstatus, status = '$status', timer = 1, timeractivatedon = NOW(),  lastupdated = NOW() WHERE id = $this->orderNo";
            }
            else{
                $sql = "UPDATE user_orders SET paymentstatus = $paymentstatus, status = '$status', lastupdated = NOW() WHERE id = $this->orderNo";
            }
            $update = $this->queryManager->query($sql);

            if (!$update){
                $this->message = 'Order status is not updated successfully. Please contact us with this message';
                return;
            }
            else{
                if (!empty($this->promoType) && $this->promoType == 'B'){
                    $sql = "UPDATE user_orders_promotion_b_status, user_mapping SET 
                            status4B_apple = 1, status5B_apple = 1, status6B_apple = 1, status7B_apple = 1, 
                            user_orders_promotion_b_status.lastupdated = NOW()  
                            WHERE user_mapping.userorderstatusid = user_orders_promotion_b_status.iduser_orders_promotion_b_status AND user_mapping.orderid = $this->orderNo ";
                    
                     $update = $this->queryManager->query($sql);
                }
            }
        }
        
        if ($this->getIsCreditAvailableMode()){
            $sql = "INSERT INTO user_payments (orderid, transactionid, paymentmethod, amountcharged, email, createdon) VALUES ($this->orderNo, 'NA', 'Credit Available', $this->amountcharged, '$this->paymentEmail', NOW())";
            $insert = $this->queryManager->query($sql);
        }
        else{
            $response = $_SESSION['payflowresponse'];
            $paymentmethod = '';
            if ($response['TENDER'] == 'CC' || $response['TENDER'] == 'C'){
                $paymentmethod = 'Credit Card';
             }
             else if ($response['TENDER'] == 'P'){
                 $paymentmethod = 'PayPal';
             }

             $transactionid = $response['PNREF'];
             $amountcharged = $response['AMT'];
             $email = $response['EMAIL'];

            $sql = "INSERT INTO user_payments (orderid, transactionid, paymentmethod, amountcharged, email, createdon) VALUES ($this->orderNo, '$transactionid', '$paymentmethod', $amountcharged, '$email', NOW())";
            $insert = $this->queryManager->query($sql);
        }
        $this->message = 'Your order status is updated,';
        
        //Generate receipt
        $folderName = $this->orderNo . '-' . $this->clientid;
        $customerDirLocation = Config::getCustomerFolder_location().$folderName;

        if (file_exists($customerDirLocation)){
            $currentdate = date('Y-m-d-h-i-s');
            $fileName = Config::$customerOrders_transactionFile_name.$this->orderNo.'_'.$currentdate.'.pdf';
            
            $PDFReportGenerator = new PDFReportGenerator();
            $PDFReportGenerator->generatePDF($customerDirLocation.DIRECTORY_SEPARATOR.$fileName);
            
            $this->message = $this->message .' your transaction receipt is available in your folder ';
        }
        else{
            $this->message = $this->message . ' Your order folder not found on server. Please contact us with this message';
        }
        
        //Send Mail
        $profile = new Profile();
        $profile->fetchBasedOnLoginid($this->clientid);
        
        if ($updateStatus){
            $mailInfo = array(
                        'fname' => $profile->getFName(),
                        'lname' => $profile->getLName(),
                        'email' => $profile->getEmail(),
                        'orderno' => $this->orderNo,
                        'project' => $this->projectName,
                        'noofemails' => $this->noOfRecords
                        );
            
            $emailNotification = new EmailNotification();
            $mail = $emailNotification->sendStatusEmail($mailInfo, $status);
            if ($mail){
                $this->message = $this->message . ' and a confirmation email sent to you.';
            }
            else{
                $this->message = $this->message . ' Failed to send Confirmation mail. Please contact us with this message';
            }
        }
        
    }
    
    function updateCreditAvailabePaymentStatusAndGenerateReceipt(){
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());  
        
        $balance = $this->payAmount;
        
        $sql = "SELECT * FROM user_orders, user_mapping WHERE user_orders.id = user_mapping.orderid AND loginid = $this->clientid AND paymentstatus = ".Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED." AND creditbalance IS NOT NULL AND creditbalance > 0 ";
        $result = $this->queryManager->query($sql);
        
        if ($result){
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                
                if ($balance > 0){
                    $creditbalance = $row['creditbalance'];
                    $amountLeft;
                    if ($creditbalance > $balance){
                        $amountLeft = $creditbalance - $balance;
                    }
                    else{
                        $amountLeft = 0;
                    }

                    $sql = "UPDATE user_orders SET creditbalance = ".$amountLeft.", lastupdated = NOW() WHERE id = ".$row['orderid'];
                    $update = $this->queryManager->query($sql);
                    if ($update){
                        $this->setPaymentEmail($row['email']);
                        $balance = $balance - $creditbalance;
                    }
                }
                else{
                    break;
                }
            }
            
            if ($balance <= 0){
                //Update the payment status and generate report if the balance is '0' now
                $this->amountcharged = $this->payAmount;
                
                $this->setIsCreditAvailableMode(TRUE);
                $this->updatePaymentStatusAndGenerateReceipt(TRUE);
                $_SESSION['userOrder'] = $this;
            }
            else if ($balance > 0){
               //Dont' Update the payment status but generate report for this transaction
                $this->amountcharged = $this->payAmount - $balance;
                $this->setIsCreditAvailableMode(TRUE);
                $this->updatePaymentStatusAndGenerateReceipt(FALSE);
                
                 //Charge the leftover balance from Paypal Advanced
                $this->setIsCreditAvailableMode(FALSE);
                $this->setPayflowAmount($balance);
                $_SESSION['userOrder'] = $this;
                header('location: ../securezone/checkout.php');
                exit(0);
                
            }
            else{
                $this->message = 'Could not able to deduct the amount from credited balance. Please contact us with this message.';
            }
            
        }
        
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
    
    public function getIsPromotionApplied() {
        return $this->isPromotionApplied;
    }

    public function setIsPromotionApplied($isPromotionApplied) {
        $this->isPromotionApplied = $isPromotionApplied;
    }



}

?>
