<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';

if (session_id() == "") 
    session_start();

$message = NULL;

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {

     if (isset($_POST['WarningMessage_x']) && isset($_POST['WarningMessage_y'])){

        $order = new Order();

        $orderid = $_POST['orderid'];
        $clientid = $_POST['clientid'];
        $order->setOrderNo($orderid);
        $order->setClientid($clientid);

        $flag = $order->deleteUserOrders();
        $message = '';
        
        if ($flag == 2){
            $message = 'Order is deleted successfully.';
        }
        else if ($flag == 1){
            $message = "Failed to find customer dir : ".$orderid.'-'.$clientid;
        }
        else {
            $message = 'Order not found.';
        }
        
        header("location: ../securezone/WarningMessage.php?message=".$message);
        exit(0);
    }

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - Documents</title>
<link type="text/css" rel="stylesheet" href="../css/GAMmain.css">
<!--<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url(../Images/BackgdGAMsite.jpg);
	background-repeat: repeat-x;
	background-color: #B4C2CB;
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
</head>

<body onload="MM_preloadImages('../Images/GAMButtonSaveON.jpg')">

<div align="center">
  <table width="1000" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td height="104" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1000" height="104" valign="top"><iframe src="../securezone/header.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="../Images/GAMPageTitleDocuments.jpg" name="Image1" width="385" height="60" id="Image1" /></td>
          </tr>
      </table></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="615" height="60" valign="top"><iframe src="../securezone/submenu.php" width="615" height="60" frameborder="0" scrolling="no" ></iframe></td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td height="36" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="241" height="36" valign="top"><img src="../Images/GAMUserLowBarPlaceOrderNOACTIVE.jpg" width="241" height="36" /></td>
            <td width="759" valign="top"><img src="../Images/GAMUserLowBarREST.jpg" width="759" height="36" /></td>
          </tr>
        
      </table></td>
    </tr>
    
    <?php
        if ($message){ ?>
            <tr>
                <td  colspan="3" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo $message; ?> 
                </p></td>
            </tr>
        <?php }
         if (!empty($_SESSION['$promoMessage'])){ ?>
            <tr>
                <td  colspan="3" height="20" valign="top" bgcolor="#FF0505" align="left" ><p align="justify" class="style1111"><?php echo $_SESSION['$promoMessage']; ?> 
                </p></td>
            </tr>
       <?php 
        unset($_SESSION['$promoMessage']);
         }
       ?>
    <tr>
      <td width="21" height="10"></td>
      <td colspan="2" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="958" height="47" valign="top" bgcolor="#FFFFFF" ><img src="../Images/Bartopbottom.jpg" width="958" height="47" /></td>
          </tr>
        <tr>
          <td height="405" align="center" valign="middle" bgcolor="#222641" ><iframe src="../securezone/userTable.php" width="900" height="400" frameborder="0" scrolling="yes" ></iframe></td>
          </tr>
        <tr>
          <td height="47" valign="top" ><img src="../Images/Bartopbottom2.jpg" width="958" height="47" /></td>
          </tr>
        
        
        
        
        
        
        
        
        

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </table></td>
      <td width="21"></td>
    </tr>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <tr>
      <td height="227" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="21" height="135" valign="top"><img src="../Images/GAMLEFTspace.jpg" width="21" height="135" /></td>
          </tr>
        <tr>
          <td height="92">&nbsp;</td>
          </tr>
      </table></td>
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="21" height="143" valign="top"><img src="../Images/GAMRIGHT-space.jpg" width="21" height="143" /></td>
          </tr>
        <tr>
          <td height="84">&nbsp;</td>
          </tr>
        
        
      </table></td>
    </tr>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <tr>
      <td height="262"></td>
      <td></td>
    </tr>
    <tr>
      <td height="160"></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="958" height="160" valign="top"><iframe src="../securezone/footer.php" width="958" height="160" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
      <td></td>
    </tr>
    <tr>
      <td height="1"></td>
      <td width="364"></td>
      <td width="594"></td>
      <td></td>
    </tr>
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