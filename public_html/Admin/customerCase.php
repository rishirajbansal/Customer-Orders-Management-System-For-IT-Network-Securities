<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'CustomersOrders.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $clientid = $_GET['clientid'];
    
    $customersOrders = new CustomersOrders();
    
    $customersOrders->fetchOrders($clientid);
    $customersOrders->fetchPromotionB_Orders($clientid);
    $customersOrders->fetchCancelledOrders($clientid);
    
    $cancelledOrders = $customersOrders->getCancelledOrders();
    $orders = $customersOrders->getOrders();
    $promotionB_orders = $customersOrders->getPromotionB_orders();
    
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Order Wire</title>
<link type="text/css" rel="stylesheet" href="../css/userjobs.css">
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
function popupnow(url) {
	newwindow=window.open(url,'name','height=200,width=400,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
//-->
</script>


</head>

<body>
<div align="center">
  <table width="1013"  border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <?php 
        if (!empty($cancelledOrders)){  ?>
            <tr>
              <td width="1013" height="410" valign="top"><iframe src="../Admin/COCredit.php?clientid=<?php echo $clientid; ?>" width="1010" height="410" frameborder="0" scrolling="no" ></iframe> </td>
           </tr>
       <?php }
    ?>
    
    <?php
    if (!empty($orders)){
        $_SESSION['$adminCustomerOrders'] = $orders;
        $_SESSION['$adminCustomerOrdersCtr'] = 1;
        foreach ($orders as $record) {
            ?>
            <tr>
              <td width="1013" height="410" valign="top"><iframe src="../Admin/COCasePAID.php" width="1010" height="410" frameborder="0" scrolling="no" ></iframe> </td>
           </tr>
        
        <?php }
    }
    
    ?>
    
     <?php
    if (!empty($promotionB_orders)){
        $_SESSION['$adminCustomerPromotionBOrders'] = $promotionB_orders;
        $_SESSION['$adminCustomerPromotionBOrdersCtr'] = 1;
        foreach ($promotionB_orders as $record) {
            ?>
            <tr>
                <td height="410" valign="top"><iframe src="../Admin/COCasePromoB.php" width="1010" height="410" frameborder="0" scrolling="no" ></iframe></td>
             </tr>
        
        <?php }
    }
    
    ?>
    </table>
</div>
</body>
</html>

<?php
}
else{
    header("location: ../login.php");
}


?>