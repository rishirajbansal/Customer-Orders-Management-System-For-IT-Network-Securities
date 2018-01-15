<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Accounting.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $customerOrders = NULL;
    $grandUnits = 0;
    $grandTotal = 0.00;
    
    if (!empty($_SESSION['accounting'])){
        $accounting = $_SESSION['accounting'];
        
        if ($accounting->getOptionType() == 1){
            $customerOrders = $accounting->getCustomer();
        }
        else if ($accounting->getOptionType() == 2){
            $customerOrders = $accounting->getAllCustomers();
        }
        else if ($accounting->getOptionType() == 3){
            $customerOrders = $accounting->getCreditAvailableCustomers();
        }
        
        unset($_SESSION['accounting']);
        
    }
           
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Accounting Customer Table</title>
<link type="text/css" rel="stylesheet" href="../css/form.css">
<!--<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url(../Images/BKGDTEXTURE.jpg);
	background-repeat: repeat;
}
.style1 {
	color: #666666;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	margin-right: 20px;
	margin-left: 20px;
	line-height: 22px;
}
a:link {
	color: #666666;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #666666;
}
a:hover {
	text-decoration: underline;
	color: #666666;
}
a:active {
	text-decoration: none;
	color: #666666;
}
.style12 {
	font-size: 10px;
	color: #666666;
	font-family: Arial, Helvetica, sans-serif;
}
.style3 {
    font-size: 18px;
    color: #92B5DF;
	font-weight: bold;
	}
-->
</style>
<script type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=200,width=300,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
</head>

<body>

<div align="left">
  <?php
    if (!$customerOrders) { ?>
        <center><strong>No Records</strong></center>
    <?php } else { ?>
  <table width="899"  height="520" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="900" height="226" align="left" valign="top" >
          <?php  
          
            $ctr = 0;
            if ($accounting->getPageCount() == 1){
                $ctr = 1;
            }
            else{
                $ctr = (($accounting->getPageCount() - 1) * Config::$accountingMaxCustomerOnPage) + 1;
            }
          
            foreach ($customerOrders as $custRecord) {
                $customeid = $custRecord['customerid'];
                $fname = $custRecord['fname'];
                $lname = $custRecord['lname'];
                $companyName = $custRecord['company'];
                $email = $custRecord['email'];
                
                $orders = $custRecord['orders'];
                
                ?>
                <table width="900" border="1" align="center" cellpadding="0" cellspacing="0" >
                    <tr  align="center" class="style1">
                      <td height="35" colspan="2"><strong>#</strong></td>
                        <td width="154"><strong>Customer ID</strong> </td>
                        <td width="112" ><strong>First name</strong> </td>
                        <td width="106" ><strong>Last name</strong></td>
                        <td width="213" ><strong>Company</strong></td>
                        <td width="265" ><strong>Email</strong></td>
                      </tr>
                    <tr  align="center" class="style1">
                      <td height="35" colspan="2"><?php echo $ctr; ?></td>
                        <td><?php echo $customeid ?></td>
                        <td><?php echo $fname ?></td>
                        <td><?php echo $lname ?></td>
                        <td><?php echo $companyName ?></td>
                        <td><?php echo $email ?></td>
                      </tr>

                    <tr>

                      <td height="150"></td>
                        <td colspan="6" valign="top">
                         <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="265" height="30" align="center" valign="top" class="style1"><strong>Order No. </strong></td>
                              <td width="161" align="center" valign="top" class="style1"><strong>Date</strong></td>
                              <td width="248" align="center" valign="top" class="style1"><strong>Units</strong></td>
                              <td width="222" align="center" valign="top" class="style1"><strong>Amount</strong></td>
                              </tr>
                             
                             <?php
                                $totalUnits = 0;
                                $totalPrice = 0.00;
                                 foreach ($orders as $orderRecord) {
                                     $orderid = $orderRecord['orderno'];
                                     $date = $orderRecord['date'];
                                     $units = $orderRecord['units'];
                                     $amount = $orderRecord['amount'];
                                     
                                     $totalUnits = $totalUnits + $units;
                                     $totalPrice = $totalPrice + $amount;
                                     
                                     ?>
                             
                                    <tr>
                                      <td height="30" align="center" valign="top" class="style1"><?php echo $orderid ?></td>
                                      <td width="161" align="center" valign="top" class="style1"><?php echo $date ?></td>
                                      <td width="248" align="center" valign="top" class="style1"><?php echo $units ?></td>
                                      <td width="222"  align="center" valign="top" class="style1"><?php echo '$'.$amount ?></td>
                                    </tr>
                                     
                                <?php  }
                             ?> 
                            
                          <tr>
                            <td height="30" align="center" valign="top" class="style1">  </td>
                            <?php if ($accounting->getOptionType() == 3){ ?>
                              <td width="161" align="center" valign="top" class="style1"><strong>TOTAL CREDIT.........</strong></td>
                            <?php } else{ ?>
                                <td width="161" align="center" valign="top" class="style1"><strong>TOTAL.................</strong></td>
                            <?php } ?>
                              <td width="248" align="center" valign="top" class="style1"><strong><?php echo number_format($totalUnits) ?></strong></td>
                              <td width="222"  align="center" valign="top" class="style1"><strong><?php echo '$'.number_format($totalPrice, 2) ?></strong></td>
                                          </tr>

                        </table>
                        </td>
                      </tr>





                  </table>

            <?php 
                $grandUnits = $grandUnits + $totalUnits;
                $grandTotal = $grandTotal + $totalPrice;
                
                $ctr+=1;
            }
            
          ?>
       
	  
     </td>
    </tr>
    <tr>
        <?php 
        if ($accounting->getPageCount() == $accounting->getTotalPages()){
        ?>
      <td height="30" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="269" height="30">&nbsp;  </td>
                  <td width="161" align="center" valign="top" class="style1"><strong>GRAND TOTAL:</strong></td>
                  <td width="248" align="center" valign="top" class="style1"><strong><?php echo number_format($accounting->getGrandUnits()) ?></strong></td>
                  <td width="222"  align="center" valign="top" class="style1"><strong><?php echo '$'.number_format($accounting->getGrandTotal(), 2) ?></strong></td>
          </tr>
      </table></td>
        <?php } ?>
    </tr>
    <tr>
      <td height="26" >&nbsp;</td>
    </tr>
  </table>
  <?php } ?>
</div>
</body>
</html>


<?php
}
else{
    header("location: ../login.php");
}


?>