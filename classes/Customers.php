<?php

include_once 'DatabaseConnectionManager.php';
include_once 'DatabaseQueryManager.php';
include_once 'Constants.php';
include_once 'Profile.php';
include_once 'Order.php';
include_once 'CustomersOrders.php';

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';

/**
 * Description of Customers
 *
 * @author Rishi
 */
class Customers {
    
    private $optionType;
    private $filterSortBy;
    private $filterTextValue;
    private $pageCount;
    
    private $allCustomers;
    
    private $totalPages;
    private $pageFlag;
    
    private $reportData;
    
    private $message;
    
    private $connectionManager;
    private $queryManager;
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    public function fetchAllCustomers($all){
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
        
        $offset = ($this->pageCount-1) * Config::$customersMaxCustomerOnPage;
        
        $sql = "SELECT * FROM user_login, user_profile WHERE user_login.id = user_profile.loginid AND user_login.verified = 1";
        $result = $this->queryManager->query($sql);
        $count = $this->queryManager->fetchCount($result);
        $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
        
        switch ($this->optionType) {
            case "1":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY loginid ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY loginid DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND loginid LIKE '%$this->filterTextValue%' ORDER BY loginid ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY loginid ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "2":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY fname ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY fname DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND fname LIKE '%$this->filterTextValue%' ORDER BY fname ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY fname ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "3":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY lname ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY lname DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND lname LIKE '%$this->filterTextValue%' ORDER BY lname ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY lname ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "4":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY companyname ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY companyname DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND companyname LIKE '%$this->filterTextValue%' ORDER BY companyname ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY companyname ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "5":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY city ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY city DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND city LIKE '%$this->filterTextValue%' ORDER BY city ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY city ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "6":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY state ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY state DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND state LIKE '%$this->filterTextValue%' ORDER BY state ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY state ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "7":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY zipcode ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY zipcode DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND zipcode LIKE '%$this->filterTextValue%' ORDER BY zipcode ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY zipcode ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "8":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY country ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY country DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND country LIKE '%$this->filterTextValue%' ORDER BY country ";
                    
                    $result2 = $this->queryManager->query($sql);
                    $count = $this->queryManager->fetchCount($result2);
                    $this->totalPages = $this->calculateTotalPages($count, Config::$customersMaxCustomerOnPage);
                }
                else {
                    $sql = $sql . " ORDER BY country ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;
                
            case "9":
                if ($this->filterSortBy == "ASC"){
                    $sql = $sql . " ORDER BY email ASC ";
                }
                else if ($this->filterSortBy == "DESC"){
                    $sql = $sql . " ORDER BY email DESC ";
                }
                else if ($this->filterSortBy == "text"){
                    $sql = $sql . " AND email LIKE '%$this->filterTextValue%' ORDER BY email ";
                }
                else {
                    $sql = $sql . " ORDER BY email ";
                }
                
                if (!$all){
                    $sql = $sql . " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                    
                break;

            default:
                if (!$all){
                    $sql = $sql + " LIMIT $offset, " . Config::$customersMaxCustomerOnPage;
                }
                break;
        }
        
        $result = $this->queryManager->query($sql);
        
        if ($result){
            $ctr1 = 1;
            $reportCounter = 1;
            
            //For Excel Report Generation
            if ($all){
                $reportArray[$reportCounter] = array ('#', 'Customer ID', 'First Name', 'Last Name', 'Company', 'Email', 'Address1', 'Address2', 'Country', 'City', 'State', 'Zip Code', 'Phone', '#');
            }

            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                
                $custRecord = array(
                                'ctr'               =>  $ctr1,
                                'customerid'        =>  $row['loginid'],
                                'fname'             =>  $row['fname'],
                                'lname'             =>  $row['lname'],
                                'company'           =>  $row['companyname'],
                                'email'             =>  $row['email'],
                                'address1'          =>  $row['address1'],
                                'address2'          =>  $row['address2'],
                                'country'           =>  $row['country'],
                                'city'              =>  $row['city'],
                                'state'             =>  $row['state'],
                                'zipcode'           =>  $row['zipcode'],
                                'phone'             =>  $row['phone']
                               );
                
                if ($all){
                    $reportArray[++$reportCounter] = array ($custRecord['ctr'], $custRecord['customerid'], $custRecord['fname'], $custRecord['lname'], $custRecord['company'], $custRecord['email'], $custRecord['address1'], $custRecord['address2'], $custRecord['country'], $custRecord['city'], $custRecord['state'], $custRecord['zipcode'], $custRecord['phone'], $custRecord['ctr']);
                }
                
                $customers[$ctr1] = $custRecord;
                $ctr1+=1; 
                //$reportCounter+=1;
            }
            
            if ($this->filterSortBy != "text"){
                $this->filterTextValue = "";
            }
            
            if (empty($customers)){
                $this->pageCount = 0;
                $this->totalPages = 0;
            }
            $this->setAllCustomers($customers);
            $this->setReportData($reportArray);
        }
    }
    
    public function deleteCustomer($clientid){
        
        $sql = "SELECT * FROM user_mapping WHERE loginid = $clientid";
        $result = $this->queryManager->query($sql);
        
        if ($result){
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                //Delete user orders
                $orderObj = new Order();
                $orderObj->setOrderNo($row['orderid']);
                $orderObj->setClientid($row['loginid']);

                $customerOrders = new CustomersOrders();
                $customerOrders->setOrder($orderObj);
                $flag = $customerOrders->deleteUserOrdersPermanently();               
            }
        }
        
        
        $profile = new Profile();
       $profile->deleteUserProfile($clientid);
        
        $this->message = $this->message ."Customer with client id : $clientid is deleted successfully. All the orders and folder/files are deleted.".  mysql_error();
        
    }
    
     public function downloadReport($reportData){
        $report = new ExcelReportGenerator('UTF-8', false, Config::$reportFileName_customers_sheetName);
        $report->addArray($reportData);
 
        $report->generateXML(Config::$reportFileName_customers_allCustomers.date('Y-m-d-h-i'));
    }
    
    public function calculateTotalPages($count, $maxCount) {
        $totalPages = (int)($count / $maxCount);
        
        if (!($count % $maxCount == 0)) {
            $totalPages++;
        }

        return $totalPages;
    }
    
    public function getOptionType() {
        return $this->optionType;
    }

    public function setOptionType($optionType) {
        $this->optionType = $optionType;
    }

    public function getFilterSortBy() {
        return $this->filterSortBy;
    }

    public function setFilterSortBy($filterSortBy) {
        $this->filterSortBy = $filterSortBy;
    }

    public function getFilterTextValue() {
        return $this->filterTextValue;
    }

    public function setFilterTextValue($filterTextValue) {
        $this->filterTextValue = $filterTextValue;
    }

    public function getPageCount() {
        return $this->pageCount;
    }

    public function setPageCount($pageCount) {
        $this->pageCount = $pageCount;
    }

    public function getAllCustomers() {
        return $this->allCustomers;
    }

    public function setAllCustomers($allCustomers) {
        $this->allCustomers = $allCustomers;
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
    
    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }






}

?>
