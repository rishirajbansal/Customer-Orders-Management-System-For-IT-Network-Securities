<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Customers.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Accounting.php';

if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    if (isset($_GET['report']) && $_GET['report'] == 'accounting'){
        if (!empty($_SESSION['accountingXLS'])){
            $reportData = $_SESSION['accountingXLS'];
            $accounting = new Accounting();
            $accounting->downloadReport($reportData, $_GET['type']);
        }
    }
    else if (isset($_GET['report']) && $_GET['report'] == 'customers'){
        if (!empty($_SESSION['customersXLS'])){
            $reportData = $_SESSION['customersXLS'];
            $customers = new Customers();
            $customers->downloadReport($reportData);
        }
    }
    
}

?>