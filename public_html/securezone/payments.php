<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'UserOrder.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Promotion.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'DatabaseConnectionManager.php';
include_once '../functions.php';

if (session_id() == "") 
    session_start();

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
   
    $order = !empty($_SESSION['userOrder']) ? $_SESSION['userOrder'] : NULL;
    $promo_detail = !empty($_SESSION['userOrder_promodetail']) ? $_SESSION['userOrder_promodetail'] : NULL;
    
    if (isset($_SESSION['promoSubmit'])){
        $databaseConnectionManager = new DatabaseConnectionManager();
        $databaseConnectionManager->createConnection();
        $promoCode = mysql_real_escape_string($_SESSION['pcode']);
        
        if (!empty($promo_detail)){
            $promoADetail = $promo_detail['promoADetail'];
            $promoBDetail = $promo_detail['promoBDetail'];
            $matchPromoA = 0;
            $matchPromoB = 0;
            
            if (!empty($promoADetail) && $promoCode == $promoADetail['promocode']) {
                $matchPromoA = 1;
            }
            if (!empty($promoBDetail) && $promoCode == $promoBDetail['promocode']) {
                $matchPromoB = 1;
            }
            
            if (!$matchPromoA && !$matchPromoB){
                $action['result'] = 'error';
                array_push($text,'Sorry, promo code is not vaild.');
            }
            else{
                $order->setPromoCode($promoCode);
                $result = $order->applyPromotion();
                if ($result){
                    $_SESSION['userOrder'] = $order;
                    $action['result'] = 'success';
                    $promo_detail = !empty($_SESSION['userOrder_promodetail']) ? $_SESSION['userOrder_promodetail'] : NULL;
                    $message = 'Promotion code is applied successfully.';
                     if (!empty($promoBDetail) && $promoCode == $promoBDetail['promocode'] ){
                         $promoMessage = 'Promotion code is applied successfully.';
                         $_SESSION['$promoMessage'] = $promoMessage;
                         header('location: ../securezone/documents.php');
                    }
                }
                else{
                    $action['result'] = 'error';
                    array_push($text,'Sorry, promo code is failed to be applied. Please contact Us. Reason: ' . mysql_error());
                }
            }
        }
        else{
            $action['result'] = 'error';
            array_push($text,'Sorry, Failed to retrieve promotion details. Please contact Us.');
        }
        unset($_SESSION['promoSubmit']);
        unset($_SESSION['pcode']);
        
        $action['text'] = $text;
    }
    else if (isset($_POST['PAYFLOWsubmit_x']) && isset($_POST['PAYFLOWsubmit_y'])){
        header('location: ../securezone/checkout.php');
    }
    else if (isset($_POST['CREDITSubmit_x']) && isset($_POST['CREDITSubmit_y'])){
        if ($order->getTotalCreditedBalance() == 0){
            $action['result'] = 'error';
            array_push($text,'Sorry, you don\'t have credit balance in your account. The transaction cannot be completed.');
        }
        else{
            if ($order->getTotalCreditedBalance() >= $order->getPayAmount()){
                $order->updateCreditAvailabePaymentStatusAndGenerateReceipt();
                $_SESSION['userOrder'] = $order;
                if ($order->getIsCreditAvailableMode()){
                    header('location: ../securezone/checkoutSuccessful.php');
                }
                else{
                    $message = $order->getMessage();
                }
            }
            else{
                $order->updateCreditAvailabePaymentStatusAndGenerateReceipt();
                header('location: ../securezone/checkoutSuccessful.php');
            }
        }
        $action['text'] = $text;
    }
    else if (isset($_GET['from']) && $_GET['from'] == 'documentsPendingLink'){
        $order = new Order();
         
        $order->setOrderNo($_GET['orderid']);
        $order->setClientid($_GET['clientid']);
        
        $order->fetchSingleOrder();
        
        $promotion = new Promotion();
        $promo_detail = $promotion->isPromotionApplicable($order->getNoOfRecords());
        $promo_detail['promoApplied'] = $order->getIsPromotionApplied();
        
        $_SESSION['userOrder'] = $order;
        $_SESSION['userOrder_promodetail'] = $promo_detail;
        
        $order = $_SESSION['userOrder'];
        $promo_detail = $_SESSION['userOrder_promodetail'];
    }
    
    
    
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - Payment</title>
<link type="text/css" rel="stylesheet" href="../css/GAMmain.css">
<!--<style type="text/css">
bodyy {
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
<script type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
 <script type="text/javascript">
<!--
function showPayment(id){
	
	if(id=='creditC')	{
		
	document.getElementById('creditcard').style.display = '';
	document.getElementById('paypalpayment').style.display = 'none';
    document.getElementById('creditpayment').style.display = 'none';
    document.getElementById('payflowpayment').style.display = 'none';


}else{

	if(id=='paypalC')	{
		
	document.getElementById('paypalpayment').style.display = 'none';
	document.getElementById('creditcard').style.display = 'none';
    document.getElementById('creditpayment').style.display = 'none';
    document.getElementById('payflowpayment').style.display = '';


}else{

	if(id=='payflow')	{
		
	document.getElementById('payflowpayment').style.display = '';
        document.getElementById('paypalpayment').style.display = 'none';
	document.getElementById('creditcard').style.display = 'none';
    document.getElementById('creditpayment').style.display = 'none';


}
else{

	if(id=='creditaccountC')	{
		
    document.getElementById('creditpayment').style.display = '';
	document.getElementById('paypalpayment').style.display = 'none';
	document.getElementById('creditcard').style.display = 'none';
        document.getElementById('payflowpayment').style.display = 'none';
	

}else{

	document.getElementById('creditcard').style.display = 'none';
	document.getElementById('paypalpayment').style.display = 'none';
    document.getElementById('creditpayment').style.display = 'none';
    document.getElementById('payflowpayment').style.display = 'none';
		
	}
}}}}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<script language="javascript" type="text/javascript">
<!--
function popupnow3(url) {
        promoACode = '0';
        promoBCode = '0';
        
        <?php
         if (!empty($promo_detail)){
            $promoADetail = $promo_detail['promoADetail'];
            $promoBDetail = $promo_detail['promoBDetail'];

            if (!empty($promoADetail)) { ?>
                promoACode = '<?php echo $promoADetail['promocode']; ?>';
                <?php 
            }
            if (!empty($promoBDetail)) { ?>
                promoBCode = '<?php echo $promoBDetail['promocode']; ?>';
             <?php }
         }
         ?>  
             
         
         if (document.getElementById('promocode').value != promoACode &&
             document.getElementById('promocode').value != promoBCode){
             alert('Sorry, promo code is not vaild.');
         }
         else{
            url = url + '?pcode=' + document.getElementById('promocode').value;
            newwindow=window.open(url,'name','height=500,width=480,screenX=600,screenY=300');
            if (window.focus) {newwindow.focus()}
         }
        
        return false;
}

// -->
</script>
</head>

<body onload="MM_preloadImages('../Images/GAMButtonSaveON.jpg','../Images/GAMbuttonPayNowPayPalON.jpg','../Images/PaynowButtonON.jpg')"> 

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
          <td width="385" height="60" valign="top"><img src="../Images/GAMPagePayment.jpg" width="385" height="60" id="Image1" /></td>
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
        if ($message){ ?>
            <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1111"><?php echo $message; ?> 
                </p></td>
            </tr>
         <?php }
         if(!empty($action['result']) && $action['result'] == 'error'){  ?>
           <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1111"><?php echo show_errors($action); ?> 
                </p></td>
            </tr>
       <?php  }
       ?>
        <?php
        if (isset($_SESSION['curl_error']) && !empty($_SESSION['curl_error'])){ 
            $curlErr = $_SESSION['curl_error'];?>
            <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1111">
                        Connection Error : <br/>
                        <?php echo 'Error Code: '.$curlErr['curl_error_no'] ?> <br/>
                        <?php echo 'Error Message: '.$curlErr['curl_error_msg'] ?> <br/> <br/>
                        Please try again, if problem persists contact us with this message.
                        
                </p></td>
            </tr>
         <?php 
         unset($_SESSION['curl_error']);         
        }
        ?>
        <?php
        if (isset($_SESSION['paypal_error']) && !empty($_SESSION['paypal_error'])){ 
            $paypalErr = $_SESSION['paypal_error'];?>
            <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1111">
                        Transaction failed : <br/>
                        <?php echo 'Error Code : '.$paypalErr['RESULT'] ?> <br/>
                        <?php echo 'Error Message : '.$paypalErr['RESPMSG'] ?> <br/>
                        
                        Please try again, if problem persists contact us with this message.
                </p></td>
            </tr>
         <?php 
         unset($_SESSION['paypal_error']);    
         if (!empty($_SESSION['payflowresponse'])) {
             unset($_SESSION['payflowresponse']);    
         }
        }
        ?>
        <tr>
          <td width="958" height="44" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">Please check your Project name, Volume of records and File name. If you think this information contains errors or you need to make corrections, please go to the DOCUMENTS tab, delete this order and start a new one. At this point, for security reasons, we do not allow any changes.<br />
            </p></td>
          </tr>
        
        
        <tr>
          <td height="230" valign="top" ><form id="checkout" name="checkout" method="post" action="" >
            <div align="center">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                <!--DWLayoutTable-->
                <tr>
                  <td width="958" height="144" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="9" height="31"></td>
                            <td width="384" align="right" valign="middle"><span class="style1">Project name : </span></td>
                            <td width="285" valign="middle"><input name="item_number" type="text" class="style1" id="item_number" value=<?php echo $order->getProjectName(); ?> size="40" maxlength="60" style="border:0;" readonly=""/></td>
                            <td width="224" valign="middle" class="style12">
                                
                              <input name="payamount" type="hidden" value="<?php echo $order->getPayAmount(); ?>" id="payamount" size="10" maxlength="10"  height="20" />
                              <!--<input name="PARTNER" type="hidden" value="paypal" id="PARTNER" size="10" maxlength="10"  height="20" />
                              <input name="VENDOR" type="hidden" value="GAM2013" id="VENDOR" size="10" maxlength="10"  height="20" />
                              <input name="USER" type="hidden" value="rishiraj" id="USER" size="10" maxlength="10"  height="20" />
                              <input name="PWD" type="hidden" value="greenapple007" id="PWD" size="10" maxlength="10"  height="20" />
                              <input type="hidden" name="SECURETOKEN" value="1ZiFlwxH5ekWjXJGjNIWZ9QZg"/>
                              <input type="hidden" name="SECURETOKENID" value="9a9ea8208de1413abc3d60c8hjuik987"/> -->
                              <!--<input type="hidden" name="MODE" value="TEST"> -->
                                
                            &nbsp;</td>
                            <td width="56"></td>
                          </tr>
                    <tr>
                      <td height="31"></td>
                            <td align="right" valign="middle"><span class="style1">Volume of records :</span></td>
                            <td align="left" valign="middle"><input name="units" type="text" class="style1" value=<?php echo $order->getNoOfRecords(); ?> size="40" maxlength="60" style="border:0;"readonly="" /></td>
                            <td valign="middle" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                            <td></td>
                          </tr>
                    <tr>
                      <td height="31"></td>
                            <td align="right" valign="middle"><span class="style1">Total price :</span></td>
                            <td align="left" valign="middle"><input name="amount" type="text" class="style1" value=<?php echo '$'.$order->getTotalPrice(); ?>  size="40" maxlength="60"  height="20" style="border:0;" readonly="" /></td>
                            <td valign="middle" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                            <td></td>
                          </tr>
                    <tr>
                      <td height="31"></td>
                            <td align="right" valign="middle"><span class="style1">Estimated processing time (days) :</span></td>
                            <td align="left" valign="middle"><input name="turnaround" type="text" class="style1" value=<?php echo $order->getProcessingTime(); ?>  size="40" maxlength="60" height="20" style="border:0;" readonly="" /></td>
                            <td valign="middle" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                            <td></td>
                     </tr>
                    <?php
                        if ($promo_detail){
                        ?>
                        <tr>
                          <td height="31"></td>
                                <td align="right" valign="middle"><span class="style1">Promo code :</span></td>
                                <td align="left" valign="middle"><input name="promocode" type="text" class="style1" id="promocode" value="<?php echo $order->getPromoCode(); ?>" style="border:1;"  size="40" maxlength="60" height="20" <?php if ( !empty($promo_detail['promoApplied']) && $promo_detail['promoApplied'] == '1') { echo 'disabled="disabled"'; }?> /></td>
                                <td align="left" valign="middle" class="style12"><input name="apply" type="button" class="style1" id="apply" value="Apply" readonly="" onclick="return popupnow3('../securezone/promotionMessage.php')" <?php if ( !empty($promo_detail['promoApplied']) && $promo_detail['promoApplied'] == '1') { echo 'disabled="disabled"'; }?> /></td>
                                <td></td>
                        </tr>
                        <?php
                        }
                    ?>
                    <tr>
                      <td height="31"></td>
                            <td align="right" valign="middle"><span class="style1">PAY THIS AMOUNT  :</span></td>
                            <td align="left" valign="middle"><input name="AMT" type="text" class="style1" id="AMT" style="border:0;" value="<?php echo '$'.$order->getPayAmount(); ?>"  size="40" maxlength="60" readonly=""  height="20" /></td>
                            <td valign="middle" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                            <td></td>
                          </tr>
                    <tr>
                      <td height="20" colspan="5" align="center" valign="top"><img src="../Images/Lineblue.jpg" width="892" height="20" /></td>
                          </tr>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    </table></td>
                    </tr>
                <tr>
                  <td height="33" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="232" height="33" align="center" valign="middle"><span class="style1">SELECT PAYMENT  METHOD:</span> </td>
                           <!-- <td width="96" align="right" valign="middle" class="style1">Credit Card : </td>
                            <td width="36" align="center" valign="middle"><DIV id="creditC"  onclick="showPayment(this.id);"><input name="PaymentType" type="radio" value="CreditCard" /></DIV></td>
                            <td width="76" align="right" valign="middle" class="style1">PayPal : </td>
                            <td width="36" align="center" valign="middle"><DIV id="paypalC"  onclick="showPayment(this.id);"><input name="PaymentType" type="radio" value="PayPal" /></DIV></td> -->
                            <td width="150" align="right" valign="middle" class="style1">Credit Card / PayPal : </td>
                            <td width="36" align="center" valign="middle"><DIV id="paypalC"  onclick="showPayment(this.id);"><input name="PaymentType" type="radio" value="PayPal" /></DIV></td>
                           <td width="150" align="right" valign="middle" class="style1">Credit in my account : </td>
                            <td width="36" align="center" valign="middle"><DIV id="creditaccountC"  onclick="showPayment(this.id);"><input name="PaymentType" type="radio" value="Credit" /></DIV></td>
                            <td width="155">&nbsp;</td>
                        </tr>
                    
                    
                    
                    
                    </table></td>
                    </tr>
                <tr>
                  <td height="98" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="958" height="13" valign="top"><div id="creditcard" style="display:none;"  ><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <!--DWLayoutTable style="display:none;"-->
                        <tr>
                          <td width="25" height="33">&nbsp;</td>
                                <td colspan="9" valign="middle"  style="text-align:left;" class="style3">Credit Card 
                                  <input name="TENDER2" type="hidden" value="C" id="TENDER2" size="10" maxlength="10"  height="20" />
                                  <input name="TRXTYPE2" type="hidden" value="S" id="TRXTYPE2" size="10" maxlength="10"  height="20" /></td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Card Number : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="ACCT" type="text" class="style1" id="ACCT" style="border:1;"  size="40" maxlength="30" height="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Card Type : </span></td>
                                <td colspan="7" align="left" valign="middle">
                                  <label>
                                    <select name="CREDITCARDTYPE" class="style1" id="CREDITCARDTYPE">
                                        <option value="Visa" selected>Visa</option>
                                        <option value="MasterCard">MasterCard</option>
                                        <option value="Discover">Discover</option>
                                        <option value="Amex">American Express</option>
                                      </select>
                                    </label>				  </td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Expiration date (MM / YYYY)  : </span></td>
                                 <td colspan="4" align="left" valign="middle">
                                    <select name="EXPMONTH" class="style1" id="EXPMONTH">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                </select>
                                <select name="EXPYEAR" class="style1" id="EXPYEAR">
                                        <option value="2013" selected>2013</option>
                                        <option value="2014">2014</option>
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                </select>
                                </td>
                                <td width="54" align="right" valign="middle" class="style1">CVV : </td>
                                <td colspan="2" align="right" valign="middle"><input name="CVV2" type="text" class="style1" id="CVV2" style="border:1;"  size="10" maxlength="4" height="20" /></td>
                                <td width="102" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">First Name : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="BILLTOFIRSTNAME" type="text" class="style1" id="BILLTOFIRSTNAME" style="border:1;"  size="40" maxlength="30" height="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Last Name : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="BILLTOLASTNAME" type="text" class="style1" id="BILLTOLASTNAME" style="border:1;"  size="40" maxlength="30" height="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Billing Address  : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="BILLTOSTREET" type="text" class="style1" id="BILLTOSTREET" style="border:1;"  size="40" maxlength="150" height="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Billing City  : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="BILLTOCITY" type="text" class="style1" id="BILLTOCITY" style="border:1;"  size="40" maxlength="45" rheight="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Billing State  : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="BILLTOSTATE" type="text" class="style1" id="BILLTOSTATE" style="border:1;"  size="40" maxlength="45" rheight="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Billing Zip  : </span></td>
                                <td colspan="6" align="left" valign="middle"><input name="BILLTOZIP" type="text" class="style1" id="BILLTOZIP" style="border:1;"  size="40" maxlength="45" rheight="20" /></td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Billing Country  : </span></td>
                                <td colspan="7" align="left" valign="middle">
                                  <label>
                                    <select name="BILLTOCOUNTRY" class="style1" id="BILLTOCOUNTRY">
                                      <option value="none">*** SELECT YOUR COUNTRY ***</option>
                                      <option value="AF" >AFGHANISTAN</option>
                                      <option value="AX" >ÅLAND ISLANDS</option>
                                      <option value="AL" >ALBANIA</option>
                                      <option value="DZ" >ALGERIA</option>
                                      <option value="AS" >AMERICAN SAMOA</option>
                                      <option value="AD" >ANDORRA</option>
                                      <option value="AO" >ANGOLA</option>
                                      <option value="AI" >ANGUILLA</option>
                                      <option value="AQ" >ANTARCTICA</option>
                                      <option value="AG" >ANTIGUA AND BARBUDA</option>
                                      <option value="AR" >ARGENTINA</option>
                                      <option value="AM" >ARMENIA</option>
                                      <option value="AW" >ARUBA</option>
                                      <option value="AU" >AUSTRALIA</option>
                                      <option value="AT" >AUSTRIA</option>
                                      <option value="AZ" >AZERBAIJAN</option>
                                      <option value="BS" >BAHAMAS</option>
                                      <option value="BH" >BAHRAIN</option>
                                      <option value="BD" >BANGLADESH</option>
                                      <option value="BB" >BARBADOS</option>
                                      <option value="BY" >BELARUS</option>
                                      <option value="BE" >BELGIUM</option>
                                      <option value="BZ" >BELIZE</option>
                                      <option value="BJ" >BENIN</option>
                                      <option value="BM" >BERMUDA</option>
                                      <option value="BT" >BHUTAN</option>
                                      <option value="BO" >BOLIVIA</option>
                                      <option value="BA" >BOSNIA AND HERZEGOVINA</option>
                                      <option value="BW" >BOTSWANA</option>
                                      <option value="BV" >BOUVET ISLAND</option>
                                      <option value="BR" >BRAZIL</option>
                                      <option value="IO" >BRITISH INDIAN OCEAN TERRITORY</option>
                                      <option value="BN" >BRUNEI DARUSSALAM</option>
                                      <option value="BG" >BULGARIA</option>
                                      <option value="BF" >BURKINA FASO</option>
                                      <option value="BI" >BURUNDI</option>
                                      <option value="KH" >CAMBODIA</option>
                                      <option value="CM" >CAMEROON</option>
                                      <option value="CA" >CANADA</option>
                                      <option value="CV" >CAPE VERDE</option>
                                      <option value="KY" >CAYMAN ISLANDS</option>
                                      <option value="CF" >CENTRAL AFRICAN REPUBLIC</option>
                                      <option value="TD" >CHAD</option>
                                      <option value="CL" >CHILE</option>
                                      <option value="CN" >CHINA</option>
                                      <option value="CX" >CHRISTMAS ISLAND</option>
                                      <option value="CC" >COCOS (KEELING) ISLANDS</option>
                                      <option value="CO" >COLOMBIA</option>
                                      <option value="KM" >COMOROS</option>
                                      <option value="CG" >CONGO</option>
                                      <option value="CD" >CONGO, THE DEMOCRATIC REPUBLIC OF THE</option>
                                      <option value="CK" >COOK ISLANDS</option>
                                      <option value="CR" >COSTA RICA</option>
                                      <option value="CI" >COTE D'IVOIRE</option>
                                      <option value="HR" >CROATIA</option>
                                      <option value="CU" >CUBA</option>
                                      <option value="CY" >CYPRUS</option>
                                      <option value="CZ" >CZECH REPUBLIC</option>
                                      <option value="DK" >DENMARK</option>
                                      <option value="DJ" >DJIBOUTI</option>
                                      <option value="DM" >DOMINICA</option>
                                      <option value="DO" >DOMINICAN REPUBLIC</option>
                                      <option value="EC" >ECUADOR</option>
                                      <option value="EG" >EGYPT</option>
                                      <option value="SV" >EL SALVADOR</option>
                                      <option value="GQ" >EQUATORIAL GUINEA</option>
                                      <option value="ER" >ERITREA</option>
                                      <option value="EE" >ESTONIA</option>
                                      <option value="ET" >ETHIOPIA</option>
                                      <option value="FK" >FALKLAND ISLANDS (MALVINAS)</option>
                                      <option value="FO" >FAROE ISLANDS</option>
                                      <option value="FJ" >FIJI</option>
                                      <option value="FI" >FINLAND</option>
                                      <option value="FR" >FRANCE</option>
                                      <option value="GF" >FRENCH GUIANA</option>
                                      <option value="PF" >FRENCH POLYNESIA</option>
                                      <option value="TF" >FRENCH SOUTHERN TERRITORIES</option>
                                      <option value="GA" >GABON</option>
                                      <option value="GM" >GAMBIA</option>
                                      <option value="GE" >GEORGIA</option>
                                      <option value="DE" >GERMANY</option>
                                      <option value="GH" >GHANA</option>
                                      <option value="GI" >GIBRALTAR</option>
                                      <option value="GR" >GREECE</option>
                                      <option value="GL" >GREENLAND</option>
                                      <option value="GD" >GRENADA</option>
                                      <option value="GP" >GUADELOUPE</option>
                                      <option value="GU" >GUAM</option>
                                      <option value="GT" >GUATEMALA</option>
                                      <option value="GG" >GUERNSEY</option>
                                      <option value="GN" >GUINEA</option>
                                      <option value="GW" >GUINEA-BISSAU</option>
                                      <option value="GY" >GUYANA</option>
                                      <option value="HT" >HAITI</option>
                                      <option value="HM" >HEARD ISLAND AND MCDONALD ISLANDS</option>
                                      <option value="VA" >HOLY SEE (VATICAN CITY STATE)</option>
                                      <option value="HN" >HONDURAS</option>
                                      <option value="HK" >HONG KONG</option>
                                      <option value="HU" >HUNGARY</option>
                                      <option value="IS" >ICELAND</option>
                                      <option value="IN" >INDIA</option>
                                      <option value="ID" >INDONESIA</option>
                                      <option value="IR" >IRAN, ISLAMIC REPUBLIC OF</option>
                                      <option value="IQ" >IRAQ</option>
                                      <option value="IE" >IRELAND</option>
                                      <option value="IM" >ISLE OF MAN</option>
                                      <option value="IL" >ISRAEL</option>
                                      <option value="IT" >ITALY</option>
                                      <option value="JM" >JAMAICA</option>
                                      <option value="JP" >JAPAN</option>
                                      <option value="JE" >JERSEY</option>
                                      <option value="JO" >JORDAN</option>
                                      <option value="KZ" >KAZAKHSTAN</option>
                                      <option value="KE" >KENYA</option>
                                      <option value="KI" >KIRIBATI</option>
                                      <option value="KP" >KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>
                                      <option value="KR" >KOREA, REPUBLIC OF</option>
                                      <option value="KW" >KUWAIT</option>
                                      <option value="KG" >KYRGYZSTAN</option>
                                      <option value="LA" >LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>
                                      <option value="LV" >LATVIA</option>
                                      <option value="LB" >LEBANON</option>
                                      <option value="LS" >LESOTHO</option>
                                      <option value="LR" >LIBERIA</option>
                                      <option value="LY" >LIBYAN ARAB JAMAHIRIYA</option>
                                      <option value="LI" >LIECHTENSTEIN</option>
                                      <option value="LT" >LITHUANIA</option>
                                      <option value="LU" >LUXEMBOURG</option>
                                      <option value="MO" >MACAO</option>
                                      <option value="MK" >MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>
                                      <option value="MG" >MADAGASCAR</option>
                                      <option value="MW" >MALAWI</option>
                                      <option value="MY" >MALAYSIA</option>
                                      <option value="MV" >MALDIVES</option>
                                      <option value="ML" >MALI</option>
                                      <option value="MT" >MALTA</option>
                                      <option value="MH" >MARSHALL ISLANDS</option>
                                      <option value="MQ" >MARTINIQUE</option>
                                      <option value="MR" >MAURITANIA</option>
                                      <option value="MU" >MAURITIUS</option>
                                      <option value="YT" >MAYOTTE</option>
                                      <option value="MX" >MEXICO</option>
                                      <option value="FM" >MICRONESIA, FEDERATED STATES OF</option>
                                      <option value="MD" >MOLDOVA, REPUBLIC OF</option>
                                      <option value="MC" >MONACO</option>
                                      <option value="MN" >MONGOLIA</option>
                                      <option value="MS" >MONTSERRAT</option>
                                      <option value="MA" >MOROCCO</option>
                                      <option value="MZ" >MOZAMBIQUE</option>
                                      <option value="MM" >MYANMAR</option>
                                      <option value="NA" >NAMIBIA</option>
                                      <option value="NR" >NAURU</option>
                                      <option value="NP" >NEPAL</option>
                                      <option value="NL" >NETHERLANDS</option>
                                      <option value="AN" >NETHERLANDS ANTILLES</option>
                                      <option value="NC" >NEW CALEDONIA</option>
                                      <option value="NZ" >NEW ZEALAND</option>
                                      <option value="NI" >NICARAGUA</option>
                                      <option value="NE" >NIGER</option>
                                      <option value="NG" >NIGERIA</option>
                                      <option value="NU" >NIUE</option>
                                      <option value="NF" >NORFOLK ISLAND</option>
                                      <option value="MP" >NORTHERN MARIANA ISLANDS</option>
                                      <option value="NO" >NORWAY</option>
                                      <option value="OM" >OMAN</option>
                                      <option value="PK" >PAKISTAN</option>
                                      <option value="PW" >PALAU</option>
                                      <option value="PS" >PALESTINIAN TERRITORY, OCCUPIED</option>
                                      <option value="PA" >PANAMA</option>
                                      <option value="PG" >PAPUA NEW GUINEA</option>
                                      <option value="PY" >PARAGUAY</option>
                                      <option value="PE" >PERU</option>
                                      <option value="PH" >PHILIPPINES</option>
                                      <option value="PN" >PITCAIRN</option>
                                      <option value="PL" >POLAND</option>
                                      <option value="PT" >PORTUGAL</option>
                                      <option value="PR" >PUERTO RICO</option>
                                      <option value="QA" >QATAR</option>
                                      <option value="RE" >REUNION</option>
                                      <option value="RO" >ROMANIA</option>
                                      <option value="RU" >RUSSIAN FEDERATION</option>
                                      <option value="RW" >RWANDA</option>
                                      <option value="SH" >SAINT HELENA</option>
                                      <option value="KN" >SAINT KITTS AND NEVIS</option>
                                      <option value="LC" >SAINT LUCIA</option>
                                      <option value="PM" >SAINT PIERRE AND MIQUELON</option>
                                      <option value="VC" >SAINT VINCENT AND THE GRENADINES</option>
                                      <option value="WS" >SAMOA</option>
                                      <option value="SM" >SAN MARINO</option>
                                      <option value="ST" >SAO TOME AND PRINCIPE</option>
                                      <option value="SA" >SAUDI ARABIA</option>
                                      <option value="SN" >SENEGAL</option>
                                      <option value="CS" >SERBIA AND MONTENEGRO</option>
                                      <option value="SC" >SEYCHELLES</option>
                                      <option value="SL" >SIERRA LEONE</option>
                                      <option value="SG" >SINGAPORE</option>
                                      <option value="SK" >SLOVAKIA</option>
                                      <option value="SI" >SLOVENIA</option>
                                      <option value="SB" >SOLOMON ISLANDS</option>
                                      <option value="SO" >SOMALIA</option>
                                      <option value="ZA" >SOUTH AFRICA</option>
                                      <option value="GS" >SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>
                                      <option value="ES" >SPAIN</option>
                                      <option value="LK" >SRI LANKA</option>
                                      <option value="SD" >SUDAN</option>
                                      <option value="SR" >SURINAME</option>
                                      <option value="SJ" >SVALBARD AND JAN MAYEN</option>
                                      <option value="SZ" >SWAZILAND</option>
                                      <option value="SE" >SWEDEN</option>
                                      <option value="CH" >SWITZERLAND</option>
                                      <option value="SY" >SYRIAN ARAB REPUBLIC</option>
                                      <option value="TW" >TAIWAN, PROVINCE OF CHINA</option>
                                      <option value="TJ" >TAJIKISTAN</option>
                                      <option value="TZ" >TANZANIA, UNITED REPUBLIC OF</option>
                                      <option value="TH" >THAILAND</option>
                                      <option value="TL" >TIMOR-LESTE</option>
                                      <option value="TG" >TOGO</option>
                                      <option value="TK" >TOKELAU</option>
                                      <option value="TO" >TONGA</option>
                                      <option value="TT" >TRINIDAD AND TOBAGO</option>
                                      <option value="TN" >TUNISIA</option>
                                      <option value="TR" >TURKEY</option>
                                      <option value="TM" >TURKMENISTAN</option>
                                      <option value="TC" >TURKS AND CAICOS ISLANDS</option>
                                      <option value="TV" >TUVALU</option>
                                      <option value="UG" >UGANDA</option>
                                      <option value="UA" >UKRAINE</option>
                                      <option value="AE" >UNITED ARAB EMIRATES</option>
                                      <option value="GB" >UNITED KINGDOM</option>
                                      <option value="US" selected="selected" >UNITED STATES</option>
                                      <option value="UM" >UNITED STATES MINOR OUTLYING ISLANDS</option>
                                      <option value="UY" >URUGUAY</option>
                                      <option value="UZ" >UZBEKISTAN</option>
                                      <option value="VU" >VANUATU</option>
                                      <option value="VE" >VENEZUELA</option>
                                      <option value="VN" >VIET NAM</option>
                                      <option value="VG" >VIRGIN ISLANDS, BRITISH</option>
                                      <option value="VI" >VIRGIN ISLANDS, U.S.</option>
                                      <option value="WF" >WALLIS AND FUTUNA</option>
                                      <option value="EH" >WESTERN SAHARA</option>
                                      <option value="YE" >YEMEN</option>
                                      <option value="ZM" >ZAMBIA</option>
                                      <option value="ZW" >ZIMBABWE</option>
                                      </select>
                                    </label>				  </td>
                                <td width="104" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
					          </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Select Currency : </span></td>
                                <td colspan="7" align="left" valign="middle">
                                  <label>
                                    <select name="CURRENCYCODE" class="style1" id="CURRENCYCODE">
                                        <option value="USD" selected>USD</option>
                                        <option value="AFA">AFA</option>
                                        <option value="DZD">DZD</option>
                                        <option value="ADP">ADP</option><option value="AOA">AOA</option><option value="ARS">ARS</option>
                                        <option value="AMD">AMD</option><option value="AWG">AWG</option><option value="AZM">AZM</option>
                                        <option value="BSD">BSD</option><option value="BHD">BHD</option><option value="BDT">BDT</option>
                                        <option value="BBD">BBD</option><option value="BYR">BYR</option><option value="BZD">BZD</option>
                                        <option value="BMD">BMD</option><option value="BTN">BTN</option><option value="INR">INR</option>
                                        <option value="BOV">BOV</option><option value="BOB">BOB</option><option value="BAM">BAM</option>
                                        <option value="BWP">BWP</option><option value="BRL">BRL</option><option value="BND">BND</option>
                                        <option value="BGL">BGL</option><option value="BGN">BGN</option><option value="BIF">BIF</option>
                                        <option value="KHR">KHR</option><option value="CAD">CAD</option><option value="CVE">CVE</option>
                                        <option value="KYD">KYD</option><option value="XAF">XAF</option><option value="CLF">CLF</option>
                                        <option value="CLP">CLP</option><option value="CNY">CNY</option><option value="COP">COP</option>
                                        <option value="KMF">KMF</option><option value="CDF">CDF</option><option value="CRC">CRC</option>
                                        <option value="HRK">HRK</option><option value="CUP">CUP</option><option value="CYP">CYP</option>
                                        <option value="CZK">CZK</option><option value="DKK">DKK</option><option value="DJF">DJF</option>
                                        <option value="DOP">DOP</option><option value="TPE">TPE</option><option value="ECV">ECV</option>
                                        <option value="ECS">ECS</option><option value="EGP">EGP</option><option value="SVC">SVC</option>
                                        <option value="ERN">ERN</option><option value="EEK">EEK</option><option value="ETB">ETB</option>
                                        <option value="FKP">FKP</option><option value="FJD">FJD</option><option value="GMD">GMD</option>
                                        <option value="GEL">GEL</option><option value="GHC">GHC</option><option value="GIP">GIP</option>
                                        <option value="GTQ">GTQ</option><option value="GNF">GNF</option><option value="GWP">GWP</option>
                                        <option value="GYD">GYD</option><option value="HTG">HTG</option><option value="HNL">HNL</option>
                                        <option value="HKD">HKD</option><option value="HUF">HUF</option><option value="ISK">ISK</option>
                                        <option value="IDR">IDR</option><option value="IRR">IRR</option><option value="IQD">IQD</option>
                                        <option value="ILS">ILS</option><option value="JMD">JMD</option><option value="JPY">JPY</option>
                                        <option value="JOD">JOD</option><option value="KZT">KZT</option><option value="KES">KES</option>
                                        <option value="AUD">AUD</option><option value="KPW">KPW</option><option value="KRW">KRW</option>
                                        <option value="KWD">KWD</option><option value="KGS">KGS</option><option value="LAK">LAK</option>
                                        <option value="LVL">LVL</option><option value="LBP">LBP</option><option value="LSL">LSL</option>
                                        <option value="LRD">LRD</option><option value="LYD">LYD</option><option value="CHF">CHF</option>
                                        <option value="LTL">LTL</option><option value="MOP">MOP</option><option value="MKD">MKD</option>
                                        <option value="MGF">MGF</option><option value="MWK">MWK</option><option value="MYR">MYR</option>
                                        <option value="MVR">MVR</option><option value="MTL">MTL</option><option value="EUR">EUR</option><option value="MRO">MRO</option><option value="MUR">MUR</option><option value="MXN">MXN</option><option value="MXV">MXV</option><option value="MDL">MDL</option><option value="MNT">MNT</option><option value="XCD">XCD</option><option value="MZM">MZM</option><option value="MMK">MMK</option><option value="ZAR">ZAR</option><option value="NAD">NAD</option><option value="NPR">NPR</option><option value="ANG">ANG</option><option value="XPF">XPF</option><option value="NZD">NZD</option><option value="NIO">NIO</option><option value="NGN">NGN</option><option value="NOK">NOK</option><option value="OMR">OMR</option><option value="PKR">PKR</option><option value="PAB">PAB</option><option value="PGK">PGK</option><option value="PYG">PYG</option><option value="PEN">PEN</option><option value="PHP">PHP</option><option value="PLN">PLN</option><option value="USD">USD</option><option value="QAR">QAR</option><option value="ROL">ROL</option><option value="RUB">RUB</option><option value="RUR">RUR</option><option value="RWF">RWF</option><option value="SHP">SHP</option><option value="WST">WST</option><option value="STD">STD</option><option value="SAR">SAR</option><option value="SCR">SCR</option><option value="SLL">SLL</option><option value="SGD">SGD</option><option value="SKK">SKK</option><option value="SIT">SIT</option><option value="SBD">SBD</option><option value="SOS">SOS</option><option value="LKR">LKR</option><option value="SDD">SDD</option><option value="SRG">SRG</option><option value="SZL">SZL</option><option value="SEK">SEK</option><option value="SYP">SYP</option><option value="TWD">TWD</option><option value="TJS">TJS</option><option value="TZS">TZS</option><option value="THB">THB</option><option value="XOF">XOF</option><option value="TOP">TOP</option><option value="TTD">TTD</option><option value="TND">TND</option><option value="TRY">TRY</option><option value="TMM">TMM</option><option value="UGX">UGX</option><option value="UAH">UAH</option><option value="AED">AED</option><option value="GBP">GBP</option><option value="USS">USS</option><option value="USN">USN</option><option value="UYU">UYU</option><option value="UZS">UZS</option><option value="VUV">VUV</option><option value="VEB">VEB</option><option value="VND">VND</option><option value="MAD">MAD</option><option value="YER">YER</option><option value="YUM">YUM</option><option value="ZMK">ZMK</option><option value="ZWD">ZWD</option>
                                      </select>
                                    </label>				  </td>
                                <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                            <tr>
                          <td height="66" colspan="3" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                                <td colspan="3" align="left" valign="middle"><input name="Submit" type="image" id="Image51" onmouseover="MM_swapImage('Image51','','../Images/PaynowButtonON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/PaynowButtonOFF.jpg" /></td>
                                <td colspan="4" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                                </tr>
                        <tr>
                          <td height="11"></td>
                                <td width="303"></td>
                                <td width="18"></td>
                                <td width="92"></td>
                                <td></td>
                                <td width="104"></td>
                                <td width="3"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                        <tr>
                          <td height="1"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td width="147"></td>
                                <td></td>
                              </tr>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        </table>
                            </div></td>
                      </tr>
                        <tr>
                      
			<td height="14" valign="top"><div id="payflowpayment" style="display:none;"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <!--DWLayoutTable -->
                        <tr>
                          <td width="25" height="33">&nbsp;</td>
                          <td colspan="7" style="text-align:left;"  valign="middle" class="style3"></td>
                         </tr>
                         <tr>
                           <td height="11" colspan="8" align="center" valign="middle">
                                <input name="PAYFLOWsubmit" type="image" id="Image53" onmouseover="MM_swapImage('Image53','','../Images/PaynowButtonON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/PaynowButtonOFF.jpg"  />
                           </td>
                        </tr>
                        <tr>
                          <td height="1"></td>
                                <td width="301"></td>
                                <td width="117"></td>
                                <td width="117"></td>
                                <td width="28"></td>
                                <td width="104"></td>
                                <td width="147"></td>
                                <td width="119"></td>
                              </tr>
                        
                        </table> 
                            </div></td>
                          </tr>
                    <tr>
                      
			<td height="14" valign="top"><div id="paypalpayment" style="display:none;"><table width="100%" border="0" cellpadding="0" cellspacing="0">

                        <!--DWLayoutTable -->
                        <tr>
                          <td width="25" height="33">&nbsp;</td>
                                
				<td colspan="7" style="text-align:left;"  valign="middle" class="style3">PayPal Express Checkout 
								

                                  <!--<input type="hidden" name="cmd" value="_xclick"> 
                            <input type="hidden" name="business" value="manager@greenapplemail.com">
                            <input name="AMT" type="hidden" class="style1" id="AMT"  value=""/>
                            <input type="hidden" name="item_name" value="Email verification services" >
                            <input type="hidden" name="tax_x" value="0.00">
                            <input type="hidden" name="page_style" value="primary">	
                            <input type="hidden" name="image_url" value="http://greenapplemail.com/Images/LogoGAMpypal.jpg">
                            <input type="hidden" name="cancel_return" value="http://greenapplemail.com/securezone/payments.html">
                            <input type="hidden" name="return" value="http://greenapplemail.com/securezone/documents.html"> <br />    -->        
			
			</td>
                              </tr>
                        
                        <tr>
                          <td height="11" colspan="8" align="center" valign="middle">
						  <input name="Submit" type="image" id="Image44" onmouseover="MM_swapImage('Image44','','../Images/GAMButtonPayNowPayPalON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/GAMButtonPayNowPayPalOFF.jpg" width="249" height="40" /></td>
                                </tr>
                        <tr>
                          <td height="1"></td>
                                <td width="301"></td>
                                <td width="117"></td>
                                <td width="117"></td>
                                <td width="28"></td>
                                <td width="104"></td>
                                <td width="147"></td>
                                <td width="119"></td>
                              </tr>
                        
                        </table> 
                            </div></td>
                          </tr>
                    <tr>
                      
                          </tr>
                    <tr>
                      <td height="14" valign="top"><div id="creditpayment" style="display:none;" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        
						<!--DWLayoutTable style="display:none;"-->
                        <tr>
                          <td width="25" height="33">&nbsp;</td>
                                <td colspan="4" style="text-align:left;" valign="middle" class="style3">Credit in my Account                                 </td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Credit Available : </span></td>
                                <td colspan="2" align="left" valign="middle"><input name="CreditUser" type="text" class="style1" id="CreditUser" value="<?php echo '$'.$order->getTotalCreditedBalance(); ?>" style="border:1;"  size="30" maxlength="30" height="20" readonly="" /></td>
                                <td width="251" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Total Amount to Pay   : </span></td>
                                <td colspan="2" align="left" valign="middle"><input name="AMT" type="text" class="style1" id="AMT" value="<?php echo '$'.$order->getPayAmount(); ?>" style="border:1;"  size="30" maxlength="30"  height="20" readonly="" /></td>
                                <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
                        <tr>
                          <td height="35" colspan="2" align="right" valign="middle" ><span class="style1">Difference to pay or Credit Remaining : </span></td>
                          <td colspan="2" align="left" valign="middle"><input name="CreditLeft" type="text"  class="style1" id="CreditLeft" value="<?php echo '$'.$order->getTotalCreditedBalanceLeft(); ?>" style="border:1;"  size="30" maxlength="30" height="20"  readonly=""/></td>
                                <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                              </tr>
							   <tr>
                          <td height="35" >&nbsp;</td>
                                <td width="301" >&nbsp;</td>
                                <td colspan="3" align="left" valign="middle" ><span class="style1">Do you really want to pay using your available Credit? </span></td>
                                </tr>
                        
                        <tr>
                          <td height="66" colspan="3" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                                <td width="362" align="left" valign="middle">
                                    <input name="CREDITSubmit" type="image" id="Image52" onmouseover="MM_swapImage('Image52','','../Images/PaynowButtonON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/PaynowButtonOFF.jpg" />
                                </td>
                                <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                                </tr>
                        <tr>
                          <td height="11"></td>
                                <td></td>
                                <td width="19"></td>
                                <td></td>
                                <td></td>
                                </tr>
                        
                        
                        </table>  
                            </div></td>
                          </tr>
                    <tr>
                      <td height="43">&nbsp;</td>
                          </tr>
                    
                    
                    
                    </table></td>
                    </tr>
                <tr>
                  <td height="44">&nbsp;</td>
                    </tr>
                </table>
	            </div>
            </form></td>
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
      <td height="188"></td>
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