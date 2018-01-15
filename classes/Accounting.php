<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'Constants.php';

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'ExcelReportGenerator.php';


/**
 * Description of Accounting
 *
 * @author Rishi
 */
class Accounting {
    
    private $optionType;
    private $filterCustomerId;
    private $filterDateFrom;
    private $filterDateTo;
    private $pageCount;
    
    private $allCustomers;
    private $customer;
    private $creditAvailableCustomers;
    
    private $totalPages;
    private $pageFlag;
    private $grandTotal;
    private $grandUnits;
    
    private $reportData;
    
    private $connectionManager;
    private $queryManager;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    public function fetchAllCustomerOrders($all){
        $customers = array();
        $reportArray = array(1 => array());
        
        if (!$this->pageCount){
            $this->pageCount = 1;
        }
        
        if ($this->pageFlag == "first"){
            $this->pageCount = 1;
        }
        else if ($this->pageFlag == "next"){
            $this->pageCount += 1;
        }
        else if ($this->pageFlag == "last"){
            $this->pageCount = $this->totalPages;
        }
        
        $offset = ($this->pageCount-1) * Config::$accountingMaxCustomerOnPage;
        
        $sql = "SELECT * FROM user_profile WHERE loginid IN (SELECT distinct(loginid) from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND 
                paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED."))" ;
        $result1 = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result1);
        $this->totalPages = $this->calculateTotalPages($count, Config::$accountingMaxCustomerOnPage);
        
        $sql = "SELECT sum(payamount) as grandamount, sum(noofrecords) as grandunits from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND 
                paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.")" ;
        $result1 = $this->queryManager->query($sql);
        $row = $this->queryManager->fetchSingleRow($result1);
        $this->grandTotal = $row['grandamount'];
        $this->grandUnits = $row['grandunits'];
        
        $sql = "SELECT * FROM user_profile WHERE loginid IN (SELECT distinct(loginid) from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND 
                paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED."))" ;
        if (!$all){
            $sql = $sql . " LIMIT $offset, " . Config::$accountingMaxCustomerOnPage;
        }
        
        $result1 = $this->queryManager->query($sql);
        
        if ($result1){
            $ctr1 = 1;
            $reportCounter = 1;
            $grandUnits = 0;
            $grandTotal = 0.00;
            $totalUnits = 0;
            $totalPrice = 0.00;
            while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
                
                $loginid = $row1['loginid'];
                
                if (!empty($this->filterDateFrom) && !empty($this->filterDateTo)){
                    $from = date('Y-m-d', strtotime($this->filterDateFrom));
                    $to = date('Y-m-d', strtotime($this->filterDateTo));
                    $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.") AND user_orders.id = user_mapping.orderid AND user_mapping.loginid = $loginid AND user_orders.createdon BETWEEN '$from' AND ADDDATE('$to', 1)";
                }
                else{
                    $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.") AND user_orders.id = user_mapping.orderid AND user_mapping.loginid = $loginid";
                }
                
                $result2 = $this->queryManager->query($sql);
                $tempCtr = 0;
                
                if ($result2){
                    
                    //For Excel Report Generation
                    if ($all){
                        $reportArray[$reportCounter] = array ('#', 'Customer ID', 'First Name', 'Last Name', 'Company', 'Email');
                        $tempCtr = ++$reportCounter;
                        $reportArray[$tempCtr] = array();
                        $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                        $reportArray[++$reportCounter] = array('     ', 'Order No.', 'Date','Units', 'Amount');
                    }
                    
                    $orders = array();
                    $ctr2 = 1;
                    $email;
                    $totalUnits = 0;
                    $totalPrice = 0.00;
                    
                    while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                        $creationTimestampTemp = $row2['createdon'];
                        $creationTimestamp = strtotime($creationTimestampTemp);
                        $date = date('m/d/Y', $creationTimestamp);
                
                        $orderRecord = array(
                                        'orderno' => $row2['orderid'].'-'.$row2['loginid'],
                                        'date'  =>  $date,
                                        'units' =>  $row2['noofrecords'],
                                        'amount' => $row2['payamount']
                                      );
                        $email = $row2['email'];
                        $orders[$ctr2] = $orderRecord;
                        $ctr2+=1;
                        $totalUnits = $totalUnits + $orderRecord['units'];
                        $totalPrice = $totalPrice + $orderRecord['amount'];
                        //$reportCounter+=1;
                        
                        //For Excel Report Generation
                        if ($all){
                            $reportArray[++$reportCounter]  = array('     ', $orderRecord['orderno'], $orderRecord['date'],$orderRecord['units'], '$'.$orderRecord['amount']);
                        }
                    }
                    
                    if (!empty($orders)){
                        $custRecord = array(
                                    'customerid' => $row1['loginid'],
                                    'fname'  =>  $row1['fname'],
                                    'lname'  =>  $row1['lname'],
                                    'company' => $row1['companyname'],
                                    'email' => $email,
                                    'orders' => $orders
                                  );
                        //For Excel Report Generation
                        if ($all){
                            $reportArray[$tempCtr] = array($ctr1, $custRecord['customerid'], $custRecord['fname'], $custRecord['lname'], $custRecord['company'], $custRecord['email']);
                            $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                            $reportArray[++$reportCounter] = array('     ', '    ',' Total.....',number_format($totalUnits), '$'.number_format($totalPrice, 2));
                            $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                        }
                        
                        $customers[$ctr1] = $custRecord;
                        $ctr1+=1; 
                        $reportCounter+=1;
                    }
                    
                }//End of if ($result2)
                
                $grandUnits = $grandUnits + $totalUnits;
                $grandTotal = $grandTotal + $totalPrice;
                
            }//End of While
            
            if ($all){
                $reportArray[++$reportCounter] = array('     ', '    ','Grand Total.....',number_format($grandUnits), '$'.number_format($grandTotal, 2));
                $reportArray[++$reportCounter] = array('     ', '',' ','', '');
            }
            
            if (empty($customers)){
                $this->pageCount = 0;
                $this->totalPages = 0;
            }
            
            $this->setAllCustomers($customers);
            $this->setReportData($reportArray);
            
        }//End of if ($result1){
        
    }
    
    public function fetchCustomerOrdersBasedOnId($all){
        $customers = array();
        $reportArray = array(1 => array());
        
        if (!$this->pageCount){
            $this->pageCount = 1;
        }
        
        if ($this->pageFlag == "first"){
            $this->pageCount = 1;
        }
        else if ($this->pageFlag == "next"){
            $this->pageCount += 1;
        }
        else if ($this->pageFlag == "last"){
            $this->pageCount = $this->totalPages;
        }
        
        $offset = ($this->pageCount-1) * Config::$accountingMaxCustomerOnPage;
        
        $sql = "SELECT * FROM user_profile WHERE loginid = $this->filterCustomerId";
        $result1 = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result1);
        $this->totalPages = $this->calculateTotalPages($count, Config::$accountingMaxCustomerOnPage);
        
        $sql = "SELECT sum(payamount) as grandamount, sum(noofrecords) as grandunits from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND 
                loginid = $this->filterCustomerId AND paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.")" ;
        $result1 = $this->queryManager->query($sql);
        $row = $this->queryManager->fetchSingleRow($result1);
        $this->grandTotal = $row['grandamount'];
        $this->grandUnits = $row['grandunits'];
        
        $sql = "SELECT * FROM user_profile WHERE loginid = $this->filterCustomerId";        
        $result1 = $this->queryManager->query($sql);
        
        if ($result1){
            $ctr1 = 1;
            $reportCounter = 1;
            $grandUnits = 0;
            $grandTotal = 0.00;
            $totalUnits = 0;
            $totalPrice = 0.00;
            while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
             
                $loginid = $row1['loginid'];
                
                if (!empty($this->filterDateFrom) && !empty($this->filterDateTo)){
                    $from = date('Y-m-d', strtotime($this->filterDateFrom));
                    $to = date('Y-m-d', strtotime($this->filterDateTo));
                    $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.") AND user_orders.id = user_mapping.orderid AND user_mapping.loginid = $loginid AND user_orders.createdon BETWEEN '$from' AND ADDDATE('$to', 1)";
                }
                else{
                    $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID .", ".Constants::ORDER_PAYMENT_STATUS_PAID_DELETED.") AND user_orders.id = user_mapping.orderid AND user_mapping.loginid = $loginid";
                }
                
                $result2 = $this->queryManager->query($sql);
                $tempCtr = 0;
                
                if ($result2){
                    
                    //For Excel Report Generation
                    if ($all){
                        $reportArray[$reportCounter] = array ('#', 'Customer ID', 'First Name', 'Last Name', 'Company', 'Email');
                        $tempCtr = ++$reportCounter;
                        $reportArray[$tempCtr] = array();
                        $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                        $reportArray[++$reportCounter] = array('     ', 'Order No.', 'Date','Units', 'Amount');
                    }
                    
                    $orders = array();
                    $ctr2 = 1;
                    $email;
                    $totalUnits = 0;
                    $totalPrice = 0.00;
                    
                    while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                        $creationTimestampTemp = $row2['createdon'];
                        $creationTimestamp = strtotime($creationTimestampTemp);
                        $date = date('m/d/Y', $creationTimestamp);
                
                        $orderRecord = array(
                                        'orderno' => $row2['orderid'].'-'.$row2['loginid'],
                                        'date'  =>  $date,
                                        'units' =>  $row2['noofrecords'],
                                        'amount' => $row2['payamount']
                                      );
                        $email = $row2['email'];
                        $orders[$ctr2] = $orderRecord;
                        $ctr2+=1;
                        $totalUnits = $totalUnits + $orderRecord['units'];
                        $totalPrice = $totalPrice + $orderRecord['amount'];
                        //$reportCounter+=1;
                        
                        //For Excel Report Generation
                        if ($all){
                            $reportArray[++$reportCounter]  = array('     ', $orderRecord['orderno'], $orderRecord['date'],$orderRecord['units'], '$'.$orderRecord['amount']);
                        }
                    }
                    
                    if (!empty($orders)){
                        $custRecord = array(
                                    'customerid' => $row1['loginid'],
                                    'fname'  =>  $row1['fname'],
                                    'lname'  =>  $row1['lname'],
                                    'company' => $row1['companyname'],
                                    'email' => $email,
                                    'orders' => $orders
                                  );
                        //For Excel Report Generation
                        if ($all){
                            $reportArray[$tempCtr] = array($ctr1, $custRecord['customerid'], $custRecord['fname'], $custRecord['lname'], $custRecord['company'], $custRecord['email']);
                            $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                            $reportArray[++$reportCounter] = array('     ', '    ',' Total.....',number_format($totalUnits), '$'.number_format($totalPrice, 2));
                            $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                        }
                        
                        $customers[$ctr1] = $custRecord;
                        $ctr1+=1; 
                        $reportCounter+=1;
                    }
                    
                    
                }//End of if ($result2)
                
                $grandUnits = $grandUnits + $totalUnits;
                $grandTotal = $grandTotal + $totalPrice;
                
            }//End of While
            
            if ($all){
                $reportArray[++$reportCounter] = array('     ', '    ','Grand Total.....',number_format($grandUnits), '$'.number_format($grandTotal, 2));
                $reportArray[++$reportCounter] = array('     ', '',' ','', '');
            }
            
            if (empty($customers)){
                $this->pageCount = 0;
                $this->totalPages = 0;
            }
            
            $this->setCustomer($customers);
            $this->setReportData($reportArray);
            
            
        }//End of if ($result1){
        
    }
    
    public function fetchCustomerOrdersBasedOnCreditAvail($all){
        $customers = array();
        $reportArray = array(1 => array());
        
        if (!$this->pageCount){
            $this->pageCount = 1;
        }
        
        if ($this->pageFlag == "first"){
            $this->pageCount = 1;
        }
        else if ($this->pageFlag == "next"){
            $this->pageCount += 1;
        }
        else if ($this->pageFlag == "last"){
            $this->pageCount = $this->totalPages;
        }
        
        $offset = ($this->pageCount-1) * Config::$accountingMaxCustomerOnPage;
        $sql = '';
        
        if (!empty($this->filterCustomerId)){
            $sql = "SELECT * FROM user_profile WHERE loginid = $this->filterCustomerId";
        }
        else{
            $sql = "SELECT * FROM user_profile WHERE loginid IN (SELECT distinct(loginid) from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND paymentstatus = " . Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED .")";
        }
        $result1 = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result1);
        $this->totalPages = $this->calculateTotalPages($count, Config::$accountingMaxCustomerOnPage);
        
        if (!empty($this->filterCustomerId)){
            $sql = "SELECT sum(creditbalance) as grandamount, sum(noofrecords) as grandunits from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND creditbalance != 0  AND 
                paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED .") AND user_mapping.loginid = $this->filterCustomerId " ;
        }
        else{
            $sql = "SELECT sum(creditbalance) as grandamount, sum(noofrecords) as grandunits from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND creditbalance != 0  AND 
                paymentstatus IN (". Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED .")" ;
        }
        
        $result1 = $this->queryManager->query($sql);
        $row = $this->queryManager->fetchSingleRow($result1);
        $this->grandTotal = $row['grandamount'];
        $this->grandUnits = $row['grandunits'];
        
        if (!empty($this->filterCustomerId)){
            $sql = "SELECT * FROM user_profile WHERE loginid = $this->filterCustomerId ";
        }
        else{            
            $sql = "SELECT * FROM user_profile WHERE loginid IN (SELECT distinct(loginid) from user_mapping, user_orders WHERE user_mapping.orderid = user_orders.id AND paymentstatus = " . Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED ." AND creditbalance != 0)";
        }
        if (!$all){
            $sql = $sql . " LIMIT $offset, " . Config::$accountingMaxCustomerOnPage;
        }
        $result1 = $this->queryManager->query($sql);
        
        if ($result1){
            $ctr1 = 1;
            $reportCounter = 1;
            $grandUnits = 0;
            $grandTotal = 0.00;
            $totalUnits = 0;
            $totalPrice = 0.00;
            
            while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
               
                $loginid = $row1['loginid'];
                
                if (!empty($this->filterDateFrom) && !empty($this->filterDateTo)){
                    $from = date('Y-m-d', strtotime($this->filterDateFrom));
                    $to = date('Y-m-d', strtotime($this->filterDateTo));
                    $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus = " . Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED ." AND user_orders.id = user_mapping.orderid AND user_mapping.loginid = $loginid AND creditbalance != 0 AND user_orders.cancelledon BETWEEN '$from' AND ADDDATE('$to', 1)";
                }
                else{
                    $sql = "SELECT * FROM user_orders, user_mapping WHERE paymentstatus = " . Constants::ORDER_PAYMENT_STATUS_PAID_CANCELLED ." AND user_orders.id = user_mapping.orderid AND user_mapping.loginid = $loginid AND creditbalance != 0";
                }
                
                $result2 = $this->queryManager->query($sql);
                $tempCtr = 0;
                
                if ($result2){
                    
                   //For Excel Report Generation
                    if ($all){
                        $reportArray[$reportCounter] = array ('#', 'Customer ID', 'First Name', 'Last Name', 'Company', 'Email');
                        $tempCtr = ++$reportCounter;
                        $reportArray[$tempCtr] = array();
                        $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                        $reportArray[++$reportCounter] = array('     ', 'Order No.', 'Date','Units', 'Amount');
                    }

                    $orders = array();
                    $ctr2 = 1;
                    $email;
                    $totalUnits = 0;
                    $totalPrice = 0.00;
                    
                    while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

                        $cancelledOnTimestampTemp = $row2['cancelledon'];
                        $cancelledOnTimestamp = strtotime($cancelledOnTimestampTemp);
                        $date = date('m/d/Y', $cancelledOnTimestamp);
                
                        $orderRecord = array(
                                        'orderno' => $row2['orderid'].'-'.$row2['loginid'],
                                        'date'  =>  $date,
                                        'units' =>  $row2['noofrecords'],
                                        'amount' => $row2['creditbalance']
                                      );
                        $email = $row2['email'];
                        $orders[$ctr2] = $orderRecord;
                        $ctr2+=1; 
                        $totalUnits = $totalUnits + $orderRecord['units'];
                        $totalPrice = $totalPrice + $orderRecord['amount'];
                        
                         //For Excel Report Generation
                        if ($all){
                            $reportArray[++$reportCounter]  = array('     ', $orderRecord['orderno'], $orderRecord['date'],$orderRecord['units'], '$'.$orderRecord['amount']);
                        }
                    }
                    
                    if (!empty($orders)){
                        $custRecord = array(
                                    'customerid' => $row1['loginid'],
                                    'fname'  =>  $row1['fname'],
                                    'lname'  =>  $row1['lname'],
                                    'company' => $row1['companyname'],
                                    'email' => $email,
                                    'orders' => $orders
                                  );
                    
                        //For Excel Report Generation
                        if ($all){
                            $reportArray[$tempCtr] = array($ctr1, $custRecord['customerid'], $custRecord['fname'], $custRecord['lname'], $custRecord['company'], $custRecord['email']);
                            $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                            $reportArray[++$reportCounter] = array('     ', '    ',' Total.....',number_format($totalUnits), '$'.number_format($totalPrice, 2));
                            $reportArray[++$reportCounter] = array('     ', '',' ','', '');
                        }
                        
                        $customers[$ctr1] = $custRecord;
                        $ctr1+=1; 
                        $reportCounter+=1;
                    }
                }//End of if ($result2)
                
                $grandUnits = $grandUnits + $totalUnits;
                $grandTotal = $grandTotal + $totalPrice;
                
            }//End of While
            
            if ($all){
                $reportArray[++$reportCounter] = array('     ', '    ','Grand Total.....',number_format($grandUnits), '$'.number_format($grandTotal, 2));
                $reportArray[++$reportCounter] = array('     ', '',' ','', '');
            }
            
            if (empty($customers)){
                $this->pageCount = 0;
                $this->totalPages = 0;
            }
            
            $this->setCreditAvailableCustomers($customers);
            $this->setReportData($reportArray);
            
        }//End of if ($result1){
        
    }
    
    public function downloadReport($reportData, $type){
        $report = new ExcelReportGenerator('UTF-8', false, Config::$reportFileName_accounting_sheetName);
        $report->addArray($reportData);
        if ($type == 1){
            $report->generateXML(Config::$reportFileName_accounting_allCustomers.date('Y-m-d-h-i'));
        }
        else if ($type == 2){
            $report->generateXML(Config::$reportFileName_accounting_customerId.date('Y-m-d-h-i'));
        }
        else if ($type == 3){
            $report->generateXML(Config::$reportFileName_accounting_creditAvailable.date('Y-m-d-h-i'));
        }
    }
    
    public function calculateTotalPages($count, $maxCount) {
        $totalPages = (int)($count / $maxCount);
        
        if (!($count % $maxCount == 0)) {
            $totalPages++;
        }

        return $totalPages;
    }
    
    function __destruct() {
        if ($this->connectionManager->getConnection()){
            $this->connectionManager->returnConnection();
        }
    }
    
    public function getAllCustomers() {
        return $this->allCustomers;
    }

    public function setAllCustomers($allCustomers) {
        $this->allCustomers = $allCustomers;
    }
    
    public function getOptionType() {
        return $this->optionType;
    }

    public function setOptionType($optionType) {
        $this->optionType = $optionType;
    }

    public function getFilterCustomerId() {
        return $this->filterCustomerId;
    }

    public function setFilterCustomerId($filterCustomerId) {
        $this->filterCustomerId = $filterCustomerId;
    }

    public function getFilterDateFrom() {
        return $this->filterDateFrom;
    }

    public function setFilterDateFrom($filterDateFrom) {
        $this->filterDateFrom = $filterDateFrom;
    }

    public function getFilterDateTo() {
        return $this->filterDateTo;
    }

    public function setFilterDateTo($filterDateTo) {
        $this->filterDateTo = $filterDateTo;
    }

    public function getPageCount() {
        return $this->pageCount;
    }

    public function setPageCount($pageCount) {
        $this->pageCount = $pageCount;
    }
    
    public function getCustomer() {
        return $this->customer;
    }

    public function setCustomer($customer) {
        $this->customer = $customer;
    }

    public function getCreditAvailableCustomers() {
        return $this->creditAvailableCustomers;
    }

    public function setCreditAvailableCustomers($creditAvailableCustomers) {
        $this->creditAvailableCustomers = $creditAvailableCustomers;
    }
    
    public function getTotalPages() {
        return $this->totalPages;
    }

    public function setTotalPages($totalPages) {
        $this->totalPages = $totalPages;
    }
    
    public function getPageFlag() {
        return $this->pageFlag;
    }

    public function setPageFlag($pageFlag) {
        $this->pageFlag = $pageFlag;
    }
    
    public function getReportData() {
        return $this->reportData;
    }

    public function setReportData($reportData) {
        $this->reportData = $reportData;
    }

    public function getGrandTotal() {
        return $this->grandTotal;
    }

    public function setGrandTotal($grandTotal) {
        $this->grandTotal = $grandTotal;
    }

    public function getGrandUnits() {
        return $this->grandUnits;
    }

    public function setGrandUnits($grandUnits) {
        $this->grandUnits = $grandUnits;
    }




}

?>
