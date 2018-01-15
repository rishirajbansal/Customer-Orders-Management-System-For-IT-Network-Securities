<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'CustomersOrders.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1) ) {
    
    $message = NULL;
    $interval = NULL;
    
    if (isset($_POST['COCASEPAIDsubmit_x']) && isset($_POST['COCASEPAIDsubmit_y'])){
        
        $orderObj = new Order();
        setPOSTData($orderObj);
        
        $customerOrders = new CustomersOrders();
        $customerOrders->setOrder($orderObj);
        $result = $customerOrders->updateOrder();
        
        $orders = $_SESSION['$adminCustomerOrders'];
        $order = $orders[$_POST['counter']];
        $order['comments'] = $orderObj->getComments();
        $order['timer'] = $customerOrders->getOrder()->getTimer();
        $order['status'] = $orderObj->getStatus();
        $order['timepending'] = $orderObj->getTimePending();
        $order['interval'] = $orderObj->getInterval();
        
        $interval = $order['interval'];

        $statusArray = array(
                            'status1_delete'    =>  $orderObj->getStatus1_delete(),
                            'status1_apple'     =>  $orderObj->getStatus1_apple(),
                            'status1_demo'      =>  $orderObj->getStatus1_demo(),
                            'status2_delete'    =>  $orderObj->getStatus2_delete(),
                            'status2_apple'     =>  $orderObj->getStatus2_apple(),
                            'status2_demo'      =>  $orderObj->getStatus2_demo(),
                            'status3_delete'    =>  $orderObj->getStatus3_delete(),
                            'status3_apple'     =>  $orderObj->getStatus3_apple(),
                            'status3_demo'      =>  $orderObj->getStatus3_demo(),
                            'status4_delete'    =>  $orderObj->getStatus4_delete(),
                            'status4_apple'     =>  $orderObj->getStatus4_apple(),
                            'status4_demo'      =>  $orderObj->getStatus4_demo(),
                            'status5_delete'    =>  $orderObj->getStatus5_delete(),
                            'status5_apple'     =>  $orderObj->getStatus5_apple(),
                            'status5_demo'      =>  $orderObj->getStatus5_demo(),
                            'status6_delete'    =>  $orderObj->getStatus6_delete(),
                            'status6_apple'     =>  $orderObj->getStatus6_apple(),
                            'status6_demo'      =>  $orderObj->getStatus6_demo(),
                            'status7_delete'    =>  $orderObj->getStatus7_delete(),
                            'status7_apple'     =>  $orderObj->getStatus7_apple(),
                            'status7_demo'      =>  $orderObj->getStatus7_demo()
                      );

        $order['statusArray'] = $statusArray;
        $orders[$_POST['counter']] = $order;
        $_SESSION['$adminCustomerOrders'] = $orders;
        
        if ($result){
            $message = 'CHANGES SAVED';
        }
        else{
            $message = 'CHANGES NOT SAVED';
        }
    }
    else if (isset($_POST['CreditWarningMessage_x']) && isset($_POST['CreditWarningMessage_y'])){
        $orderObj = new Order();
        setPOSTDataProjectCancel($orderObj);
        
        $customerOrders = new CustomersOrders();
        $customerOrders->setOrder($orderObj);
        $result = $customerOrders->updateCancelledOrder();  
        $message = '';
        
        if ($result){
            $message = "Order is cancelled successfully.";
        }
        else{
            $message = "Order not found.";
        }
        
        if ($_SESSION['redirectedFrom'] == 'adminCustomerOrders'){
            //header("location: adminCustomerOrders.php?clientid=".$orderObj->getClientid());
            header("location: ../securezone/CreditWarningMessage.php?message=".$message);
            exit(0);
        }
        else{
            //header("location: adminorders.php");
            header("location: ../securezone/CreditWarningMessage.php?message=".$message);
            exit(0);
        }
    }
    else if (isset($_POST['WarningMessage_x']) && isset($_POST['WarningMessage_y'])){
        $orderObj = new Order();
        setPOSTDataProjectDelete($orderObj);
        
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
            $message = 'Directory not Found';
        }
        else {
            $message = 'Order not found.';
        }
        
        if ($_SESSION['redirectedFrom'] == 'adminCustomerOrders'){
            //header("location: adminCustomerOrders.php?clientid=".$orderObj->getClientid());
            header("location: ../securezone/WarningMessage.php?message=".$message);
            exit(0);
        }
        else{
            //header("location: adminorders.php");
            header("location: ../securezone/WarningMessage.php?message=".$message);
            exit(0);
        }
    }
    else{
        $orders = $_SESSION['$adminCustomerOrders'];
        $order = $orders[$_SESSION['$adminCustomerOrdersCtr']];
        $statusArray = $order['statusArray'];
        
        if (array_key_exists('interval', $order)) {
            $interval = $order['interval'];
        }
        
    }
    
   
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
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
 var days = <?php if ($interval != NULL) echo $interval->d; else echo '0';?>  
  var hours = <?php if ($interval != NULL) echo $interval->h; else echo '0'?>  
  var minutes = <?php if ($interval != NULL) echo $interval->i; else echo '0'?>  
  var seconds = <?php if ($interval != NULL) echo $interval->s; else echo '0'?> 
  var timerVar = <?php if ($interval != NULL) echo $order['timer']; else echo '0'?> 

        <!--

function popupnow(url) {
	newwindow=window.open(url,'name','height=400,width=674,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}
// -->

function setCountDown () {
  seconds--;
  if (seconds < 0){
      minutes--;
      seconds = 59
  }
  if (minutes < 0){
      hours--;
      minutes = 59
  }
  if (hours < 0){
      days--;
      hours = 23
  }
  
  if (timerVar == 1){
      document.getElementById("interactivetimer").innerHTML = days+" D : "+hours+":"+minutes+":"+seconds+"";
      SD=window.setTimeout( "setCountDown()", 1000 );
      if (days == '00' && hours == '00' && minutes == '00' && seconds == '00') { 
          seconds = "00";
          window.alert("Order Time is up.");
          window.clearTimeout(SD);
      } 
  }

}

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
function popupnow2(url) {
    
	newwindow=window.open(url,'name','height=480,width=500,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->

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
</script>
</head>

<body onload="MM_preloadImages('../Images/AdminImages/GObuttonON.jpg');<?php if ($interval != NULL) { ?>setCountDown(); <?php } ?>">
<div align="center">
    <form id="COCASEPAIDform" name="COCASEPAIDform" method="post" action="">
       <input type="hidden" name="orderid" id="orderid" value="<?php echo $order['orderid']; ?>"/>
       <input type="hidden" name="counter" id="counter" value="<?php echo $order['ctr']; ?>"/>
       <input type="hidden" name="cancelmessage" id="cancelmessage" />
       <input type="hidden" name="projectcancel" id="projectcancel" />
       <input type="hidden" name="prevStatus" id="prevStatus" value="<?php echo $order['status']; ?>"/>
  <table width="1010"  border="1" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td width="1004" height="70" valign="top"><table width="1000" height="60" border="1px" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border:solid 1px;">
        <!--DWLayoutTable-->
        <tr>
          <td width="75" height="24" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>ORDER</strong></td>
            <td width="117" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>DATE OF CREATION </strong></td>
            <td width="72" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>CLIENT ID  </strong></td>
            <td width="110" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>FIRST NAME </strong></span></td>
            <td width="115" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>LAST NAME </strong></span></td>
            <td width="150" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>COMPANY</strong></span></td>
            <td width="160" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>PROJECT NAME </strong></span></td>
            <td width="130" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>STOP TIMER </strong>
              <input  name="TimmerFreez"  type="checkbox" value="pause" <?php if ($order['timer'] == 0){ ?> checked="checked" <?php } ?> /></td>
            <td width="75" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>DELETE </strong></span></td>
	</tr>
        <tr>
          <td height="46" align="center" valign="middle" class="style12"><?php echo $order['orderNo']; ?></td>
            <td align="center" valign="middle" class="style12"><?php echo $order['creationdate']; ?></td>
            <td align="center" valign="middle" class="style12"><?php echo $order['clientid']; ?></td>
            <td align="center" valign="middle" class="style12"><?php echo $order['fname']; ?></td>
            <td align="center" valign="middle" class="style12"><?php echo $order['lname']; ?></td>
            <td align="center" valign="middle" class="style12"><?php echo $order['companyname']; ?> </td>
            <td align="center" valign="middle" class="style12"><?php echo $order['project']; ?></td>
            <td align="center" valign="middle" class="style12">
                <div id="interactivetimer">
                    <?php if ($order['timer'] == 1){
                        echo $interval->d." D : ".$interval->h.":".$interval->i.":".$interval->s."";
                    }
                    ?>
               </div>
            </td>
            
            <?php 
                if ($order['status'] == '1' || $order['status'] == '1B' || $order['status'] == '2' || $order['status'] == '3'){ ?>
                    <td align="center" valign="middle"><a onclick="return popupnow2('../securezone/CreditWarningMessage.php?amount=<?php echo $order['amount']; ?>&orderid=<?php echo $order['orderid']; ?>&counter=<?php echo $order['ctr']; ?>&clientid=<?php echo $order['clientid']; ?>&from=COCasePAID')"><img src="../Images/DeleteRound.png" width="22" height="22" border="0" /></a></td>
                <?php  }
                else{ ?>
                    <td align="center" valign="middle"><a onclick="return popupnow2('../securezone/WarningMessage.php?orderid=<?php echo $order['orderid']; ?>&counter=<?php echo $order['ctr']; ?>&clientid=<?php echo $order['clientid']; ?>&from=COCasePAID')"><img src="../Images/DeleteRound.png" width="22" height="22" border="0" /></a></td>
                    
                <?php  }
            ?>            
         </tr>
  
      </table></td>
      <td width="6"></td>
    </tr>
      <tr>
      <td height="11"></td>
      <td></td>
      </tr>
    <tr>
      <td height="260" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="100" height="260" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 0 </td>
                </tr>
            <tr>
              <td height="90" align="center" valign="middle" class="style12"><p>PENDING<br />
                PAYMENT<br />
                <br />
                FILE LOADED<br />
                IMAGE 0 </p>            </td>
                </tr>
            <tr>
              <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="42" align="center" valign="middle" bgcolor="#FFCECF" class="style12"><img src="../Images/DeleteRound.png" width="22" height="22" /></td>
                      </tr>
                
                </table></td>
                </tr>
            <tr>
              <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="42" align="center" valign="middle" bgcolor="#FFFFFF" class="style12"><img src="../Images/GREEN APPLE LOGO MINI 100PX.jpg" width="35" height="35" /></td>
                      </tr>
                
                </table></td>
                </tr>
            <tr>
              <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="42" align="center" valign="middle" bgcolor="#CCD5FF" class="style12"><img src="../Images/FolderDEMOfile.png" width="34" height="22" /></td>
                      </tr>
                
                </table></td>
                </tr>
            <tr>
              <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                    <label>
                      <input  name="Status" type="radio" value="0" <?php if ($order['status'] == '0'){ ?> checked="checked" <?php } ?> />
                      </label>                    </td>
                      </tr>
                </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 1 </td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">ORDER PAID 
                    <br />
                    <br />
                    RECEIPT PDF <br />
                    MESSAGE 1<br />
                  IMAGE 1 </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status1_delete_button" type="radio" value="1" <?php if ($statusArray['status1_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status1_delete_button" type="radio" value="0" <?php if ($statusArray['status1_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status1_apple_button" type="radio" value="1" <?php if ($statusArray['status1_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status1_apple_button" type="radio" value="0" <?php if ($statusArray['status1_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status1_demo_button" type="radio" value="1" <?php if ($statusArray['status1_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status1_demo_button" type="radio" value="0" <?php if ($statusArray['status1_demo'] == 0){ ?> checked="checked" <?php } ?>  />                    </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="1" <?php if ($order['status'] == '1'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 2 </td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">APPROVING<br />
                    DOCUMENTS<br />
                    <br />
                    MESSAGE 2 <br />
                  IMAGE 2 </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status2_delete_button" type="radio" value="1" <?php if ($statusArray['status2_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status2_delete_button" type="radio" value="0" <?php if ($statusArray['status2_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status2_apple_button" type="radio" value="1" <?php if ($statusArray['status2_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status2_apple_button" type="radio" value="0" <?php if ($statusArray['status2_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status2_demo_button" type="radio" value="1" <?php if ($statusArray['status2_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status2_demo_button" type="radio" value="0" <?php if ($statusArray['status2_demo'] == 0){ ?> checked="checked" <?php } ?> />                    </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="2" <?php if ($order['status'] == '2'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 3 </td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">ORDER IN<br />
                    PROCESS<br /> 
                    <br />
                    MESSAGE 3 <br />
                  IMAGE 3 </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status3_delete_button" type="radio" value="1" <?php if ($statusArray['status3_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status3_delete_button" type="radio" value="0" <?php if ($statusArray['status3_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status3_apple_button" type="radio" value="1" <?php if ($statusArray['status3_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status3_apple_button" type="radio" value="0" <?php if ($statusArray['status3_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status3_demo_button" type="radio" value="1" <?php if ($statusArray['status3_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status3_demo_button" type="radio" value="0" <?php if ($statusArray['status3_demo'] == 0){ ?> checked="checked" <?php } ?> />                    </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="3" <?php if ($order['status'] == '3'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 4 </td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">ORDER READY <br />
                    <br />
                    MESSAGE 4 <br />
                    IMAGE 4<br />
                  20 DAYS </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status4_delete_button" type="radio" value="1" <?php if ($statusArray['status4_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status4_delete_button" type="radio" value="0" <?php if ($statusArray['status4_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status4_apple_button" type="radio" value="1" <?php if ($statusArray['status4_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status4_apple_button" type="radio" value="0" <?php if ($statusArray['status4_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status4_demo_button" type="radio" value="1" <?php if ($statusArray['status4_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status4_demo_button" type="radio" value="0" <?php if ($statusArray['status4_demo'] == 0){ ?> checked="checked" <?php } ?> />                    </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="4" <?php if ($order['status'] == '4'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 5 </td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">ALL DATA WILL <br />
BE DELETED <br />
<br />
MESSAGE 5 <br />
IMAGE 4<br />
10 DAYS </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status5_delete_button" type="radio" value="1" <?php if ($statusArray['status5_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status5_delete_button" type="radio" value="0" <?php if ($statusArray['status5_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status5_apple_button" type="radio" value="1" <?php if ($statusArray['status5_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status5_apple_button" type="radio" value="0" <?php if ($statusArray['status5_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status5_demo_button" type="radio" value="1" <?php if ($statusArray['status5_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status5_demo_button" type="radio" value="0" <?php if ($statusArray['status5_demo'] == 0){ ?> checked="checked" <?php } ?> />                    </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="5" <?php if ($order['status'] == '5'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 6 </td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">ALL DATA WILL <br />
BE DELETED <br />
<br />
MESSAGE 6 <br />
IMAGE 4<br />
5 DAYS </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status6_delete_button" type="radio" value="1" <?php if ($statusArray['status6_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status6_delete_button" type="radio" value="0" <?php if ($statusArray['status6_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status6_apple_button" type="radio" value="1" <?php if ($statusArray['status6_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status6_apple_button" type="radio" value="0" <?php if ($statusArray['status6_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status6_demo_button" type="radio" value="1" <?php if ($statusArray['status6_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status6_demo_button" type="radio" value="0" <?php if ($statusArray['status6_demo'] == 0){ ?> checked="checked" <?php } ?> />                    </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="6" <?php if ($order['status'] == '6'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12">STATUS 7</td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12">ALL DATA WILL <br />
BE DELETED <br />
<br />
MESSAGE 7 <br />
IMAGE 4<br />
1 DAY </td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="status7_delete_button" type="radio" value="1" <?php if ($statusArray['status7_delete'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="status7_delete_button" type="radio" value="0" <?php if ($statusArray['status7_delete'] == 0){ ?> checked="checked" <?php } ?> />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="status7_apple_button" type="radio" value="1" <?php if ($statusArray['status7_apple'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="status7_apple_button" type="radio" value="0" <?php if ($statusArray['status7_apple'] == 0){ ?> checked="checked" <?php } ?> />                </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="status7_demo_button" type="radio" value="1" <?php if ($statusArray['status7_demo'] == 1){ ?> checked="checked" <?php } ?> />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="status7_demo_button" type="radio" value="0" <?php if ($statusArray['status7_demo'] == 0){ ?> checked="checked" <?php } ?> />                    </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="7" <?php if ($order['status'] == '7'){ ?> checked="checked" <?php } ?> />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="100" valign="top"><table style="border-left: solid 1px; border-right:solid 1px; display:none" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td  width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="radiobutton" type="radio" value="ON" />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="radiobutton" type="radio" value="OFF" />                </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="radiobutton" type="radio" value="ON" />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="radiobutton" type="radio" value="OFF" />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="radiobutton" type="radio" value="ON" />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="radiobutton" type="radio" value="OFF" />                    </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="ONStatus6" />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
            <td width="104" valign="top"><table style="border-left: solid 1px; border-right:solid 1px;display:none" bordercolor="#999999" width="100%" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="91" height="21" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                </tr>
                <tr>
                  <td height="90" align="center" valign="middle" class="style12"><!--DWLayoutEmptyCell-->&nbsp;</td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFCECF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFCECF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFCECF">
                        <input name="radiobutton" type="radio" value="ON" />                </td>
                          <td align="center" valign="middle" bgcolor="#FFCECF">
                          <input name="radiobutton" type="radio" value="OFF" />                </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#FFFFFF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#FFFFFF">
                        <input name="radiobutton" type="radio" value="ON" />                </td>
                          <td align="center" valign="middle" bgcolor="#FFFFFF">
                          <input name="radiobutton" type="radio" value="OFF" />                </td> 
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="42" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="48" height="19" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">ON</td>
                        <td width="43" align="center" valign="middle" bgcolor="#CCD5FF" class="style12">OFF</td>
                      </tr>
                    <tr>
                      <td height="23" align="center" valign="middle" bgcolor="#CCD5FF">
                        <input name="radiobutton" type="radio" value="ON" />                </td>
                          <td align="center" valign="middle" bgcolor="#CCD5FF">
                          <input name="radiobutton" type="radio" value="OFF" />                    </td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="21" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="91" height="21" align="center" valign="middle" bgcolor="#FF3300">
                        <label>
                          <input name="Status" type="radio" value="ONStatus7" />
                        </label>               </td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
      <td>&nbsp;</td>
    </tr>
      <tr>
      <td height="2"></td>
      <td></td>
      </tr>
    <tr>
      <td height="40" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="489" rowspan="2" align="left" valign="middle" bgcolor="#FFFFCC" style="vertical-align:middle;" >
            <label><span class="style12">COMMENTS:</span>
              <input name="comments" id="comments" type="text" class="style12" size="80" value="<?php echo $order['comments'] ?>" />
              </label>            </td>
            <td width="97" rowspan="2" align="right" valign="middle" bgcolor="#FFFFCC" class="style12" >LOAD FILES: </td>
            <td width="48" rowspan="2" align="center" valign="middle" bgcolor="#FFFFCC" ><a onclick="return popupnow('../Admin/adminLoader.php?orderid=<?php echo $order['orderid']; ?>&clientid=<?php echo $order['clientid']; ?>')"><img src="../Images/FolderImage.png" width="34" height="22" border="0" /></a></td>
            <td width="146" height="17" align="center" valign="middle" bgcolor="#FFFFCC" class="style12" > ORDER </td>
            <td width="57" rowspan="2" align="center" valign="middle" bgcolor="#FFFFCC"><input name="COCASEPAIDsubmit" type="image" src="../Images/AdminImages/GObuttonOFF.jpg" width="34" height="34" id="Image16" value="Submit" onmouseover="MM_swapImage('Image16','','../Images/AdminImages/GObuttonON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
            <td width="164" rowspan="2" align="center" valign="middle" bgcolor="#FFFFCC" class="style1"><?php echo $message; ?> </td>
          </tr>
        <tr>
          <td height="23" align="center" valign="middle" bgcolor="#FFFFCC" ><span class="style12"><?php echo $order['orderNo']; ?></span></td>
          </tr>
        
        
      </table></td>
      <td></td>
      </tr>
    </table>
    </form>
</div>
</body>
</html>

<?php

$_SESSION['$adminCustomerOrdersCtr'] += 1;
}
else{
    header("location: ../login.php");
}

function setPOSTData(Order $orderObj){
    global $prevstatus;
    
    $orderObj->setComments($_POST['comments']);
    $orderObj->setOrderNo($_POST['orderid']);
    if (!empty($_POST['TimmerFreez'])) {
        $orderObj->setTimer(0) ;
    }
    else{
        $orderObj->setTimer(1);
    }
    
    $orderObj->setStatus($_POST['Status']);
    $orderObj->setPreviousStatus($_POST['prevStatus']);
    
    $orderObj->setStatus1_delete($_POST['status1_delete_button']);
    $orderObj->setStatus1_apple($_POST['status1_apple_button']);
    $orderObj->setStatus1_demo($_POST['status1_demo_button']);
    $orderObj->setStatus2_delete($_POST['status2_delete_button']);
    $orderObj->setStatus2_apple($_POST['status2_apple_button']);
    $orderObj->setStatus2_demo($_POST['status2_demo_button']);
    $orderObj->setStatus3_delete($_POST['status3_delete_button']);
    $orderObj->setStatus3_apple($_POST['status3_apple_button']);
    $orderObj->setStatus3_demo($_POST['status3_demo_button']);
    $orderObj->setStatus4_delete($_POST['status4_delete_button']);
    $orderObj->setStatus4_apple($_POST['status4_apple_button']);
    $orderObj->setStatus4_demo($_POST['status4_demo_button']);
    $orderObj->setStatus5_delete($_POST['status5_delete_button']);
    $orderObj->setStatus5_apple($_POST['status5_apple_button']);
    $orderObj->setStatus5_demo($_POST['status5_demo_button']);
    $orderObj->setStatus6_delete($_POST['status6_delete_button']);
    $orderObj->setStatus6_apple($_POST['status6_apple_button']);
    $orderObj->setStatus6_demo($_POST['status6_demo_button']);
    $orderObj->setStatus7_delete($_POST['status7_delete_button']);
    $orderObj->setStatus7_apple($_POST['status7_apple_button']);
    $orderObj->setStatus7_demo($_POST['status7_demo_button']);
    
}

function setPOSTDataProjectCancel(Order $orderObj){
    
    $orderObj->setCancelReason($_POST['reason']);
    $orderObj->setOrderNo($_POST['orderid']);
    $orderObj->setTotalPrice($_POST['Credit_Amount']);
    $orderObj->setClientid($_POST['clientid']);
    
}

function setPOSTDataProjectDelete(Order $orderObj){
    
    $orderObj->setOrderNo($_POST['orderid']);
    $orderObj->setClientid($_POST['clientid']);
    
}




?>