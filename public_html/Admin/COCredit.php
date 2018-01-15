<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'CustomersOrders.php';

if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $customersOrders = new CustomersOrders();
    
    if (isset($_POST['WarningMessage_x']) && isset($_POST['WarningMessage_y'])){
        $orderObj = new Order();
        setPOSTDataProjectCancel($orderObj);
        
        $customerOrders = new CustomersOrders();
        $customerOrders->setOrder($orderObj);
        $flag = $customerOrders->deleteUserOrders();
        
        $message = '';
        
        if ($flag == 3){
            $message = 'Order is deleted successfully but folder not found.';
        }
        else if ($flag == 2){
            $message = 'Order is deleted successfully.';
        }
        else if ($flag == 1){
            $message = 'Order is deleted successfully but folder not found.';
        }
        else {
            $message = 'Order not found.';
        }
        
        
        header("location: ../securezone/WarningMessage.php?message=".$message);
        exit(0);
    }
    else{   
        if (!empty($_GET['clientid'])){
            $customersOrders->fetchCancelledOrders($_GET['clientid']);
        }
        else{
            $customersOrders->fetchCancelledOrders('');
        }

        $cancelledOrders = $customersOrders->getCancelledOrders();
    }
    
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Customer Credit</title>
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
</script>
</head>

<body>
<div align="center">
  <table width="1004" border="1" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td width="186" height="22" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>ORDER CANCELED</strong></td>
      <td width="181" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>PROJECT NAME CANCELED </strong></td>
            <td width="158" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>CREDIT AVAILABLE </strong></span></td>
            <td width="215" valign="top" bgcolor="#F0F0FF"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td width="92" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>DELETE</strong></td>
    </tr>
    <?php
    
    if (!empty($cancelledOrders)){ 
        foreach ($cancelledOrders as $record) {
            
        ?>
         <tr>
            <td height="35" align="center" valign="middle" class="style12"><?php echo $record['orderNo']; ?></td>
            <td align="center" valign="middle"><input name="item_number" type="text" class="style12" id="item_number" value="<?php echo $record['project']; ?>" size="25" maxlength="15" readonly=""  style="border:none"/></td>
            <td align="center" valign="middle"><input name="Credit Amount" type="text" class="style12" id="Credit Amount" value="<?php echo $record['credit']; ?>" size="20" maxlength="15" readonly=""  style="border:none"/></td>
            <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td align="center" valign="middle"><a onclick="return popupnow('../securezone/WarningMessage.php?clientid=<?php echo $record['clientid']; ?>&orderid=<?php echo $record['orderid']; ?>&from=COCredit')"><img src="../Images/DeleteRound.png" width="22" height="22" border="0" /></a></td>
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

function setPOSTDataProjectCancel(Order $orderObj){
    
    $orderObj->setOrderNo($_POST['orderid']);
    $orderObj->setClientid($_POST['clientid']);
    
}


?>