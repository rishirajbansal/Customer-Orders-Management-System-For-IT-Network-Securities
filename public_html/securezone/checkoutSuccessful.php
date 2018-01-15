<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';

if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    $order = $_SESSION['userOrder'];
    
    if (isset($_SESSION['payflowresponse']) && !empty($_SESSION['payflowresponse'])){ 
        $response = $_SESSION['payflowresponse'];
        
        //Save to database and generate report
        $order->updatePaymentStatusAndGenerateReceipt(TRUE);
    }                                            
    
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - Contact Us</title>
<link type="text/css" rel="stylesheet" href="../css/GAMmain.css">
<!--<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url(../Images/BackgdGAMsite.jpg);
	background-repeat: repeat-x;
	background-color: #B4C2CB;
}
.StyleMssg {
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
.style5 {color: #666666; font-family: Arial, Helvetica, sans-serif; font-size: 24px; margin-right: 20px; margin-left: 20px; line-height: 30px; }
-->
</style>
</head>

<body>
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
          <td width="385" height="60" valign="top"><img src="../Images/GAMPagePayment.jpg" width="385" height="60" /></td>
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
    <tr>
      <td width="21" height="10"></td>
      <td colspan="2" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <?php
        if ($order->getMessage()){ ?>
            <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1111"><?php echo $order->getMessage(); ?> 
                </p></td>
            </tr>
         <?php }
       ?>
        <tr>
          <td width="958" height="70" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="StyleMssg"> <br />
            <br />
            </p></td>
          </tr>
        <tr>
          <td height="278" valign="top" ><div align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
              <!--DWLayoutTable-->
              <tr>
                <td width="100" height="267">&nbsp;</td>
                    <td width="700" valign="top"><div align="center">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                          
                          <!--DWLayoutTable-->
                          <tr>
                            <td width="600" height="33">&nbsp;</td>
                            </tr>
                          <tr>
                            <td height="179" align="right" valign="top"><p align="left" class="style5">Congratulations !! Transaction approved! Thank you for your order.<br /><br />
                                    
                                    <b>Transaction Details: </b><br/><br/>
                                    <?php 
                                        $order = $_SESSION['userOrder'];
                                        if (isset($_SESSION['payflowresponse']) && !empty($_SESSION['payflowresponse'])){
                                            $response = $_SESSION['payflowresponse'];
                                            
                                            echo 'Transaction ID : ' .$response['PNREF'].'<br/>';
                                            echo 'Transaction Message : ' .$response['RESPMSG'].'<br/>';
                                            echo 'Amount Charged : $' .$response['AMT'].'<br/>';
                                            if ($response['TENDER'] == 'CC' || $response['TENDER'] == 'C'){
                                                echo 'Payment Method : Credit Card <br/>';
                                            }
                                            else if ($response['TENDER'] == 'P'){
                                                echo 'Payment Method :  PayPal <br/>';
                                            }
                                            
                                            if (isset($response['CARDTYPE'])){
                                                if ($response['CARDTYPE'] == '0'){
                                                    echo 'Card Type: Visa <br/>';
                                                }
                                                else if ($response['CARDTYPE'] == '1'){
                                                    echo 'Card Type: MasterCard <br/>';
                                                }
                                                else if ($response['CARDTYPE'] == '2'){
                                                    echo 'Card Type: Discover <br/>';
                                                }
                                                else if ($response['CARDTYPE'] == '3'){
                                                    echo 'Card Type: American Express <br/>';
                                                }
                                                else if ($response['CARDTYPE'] == '4'){
                                                    echo 'Card Type: Diner’s Club <br/>';
                                                }
                                                else if ($response['CARDTYPE'] == '5'){
                                                    echo 'Card Type: JCB <br/>';
                                                }
                                            }
                                            
                                            
                                            echo 'Order ID : ' .$order->getOrderNo().'<br/>';
                                            echo 'Project Name : ' .$order->getProjectName().'<br/>';
                                            echo 'Email : ' .$response['EMAIL'].'<br/>';
                                            
                                            if (!empty($_SESSION['userOrder_promodetail']) ){
                                                unset($_SESSION['userOrder_promodetail']);
                                            }
                                            if (!empty($_SESSION['payflowresponse']) ){
                                                unset($_SESSION['payflowresponse']);
                                            }
                                            
                                        }
                                        else if ($order->getIsCreditAvailableMode()){
                                            echo 'Amount Charged : $' .$order->getAmountcharged().'<br/>';
                                            echo 'Payment Method : Credit Available <br/>';
                                            echo 'Order ID : ' .$order->getOrderNo().'<br/>';
                                            echo 'Project Name : ' .$order->getProjectName().'<br/>';
                                            echo 'Email : ' .$order->getPaymentEmail().'<br/>';
                                        }
                                        
                                        unset($_SESSION['userOrder']);
                                    ?>
                              </p></td>
                          </tr>
                          <tr>
                            <td height="19">&nbsp;</td>
                          </tr>
                        </table>
                    </div></td>
                    <td width="283">&nbsp;</td>
                  </tr>
              <tr>
                <td height="11"></td>
                    <td></td>
                    <td></td>
                  </tr>
              </table>
            </div></td>
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
      <td height="111">&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td height="160">&nbsp;</td>
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