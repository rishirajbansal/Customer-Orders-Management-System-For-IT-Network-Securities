<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'UserOrder.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'DatabaseConnectionManager.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'DatabaseQueryManager.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Constants.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    
    $interval = NULL;
    
    $allProjects = $_SESSION['$allProjects'];
    $record = $allProjects[$_SESSION['$allProjectsCtr']];  
    if (array_key_exists('interval', $record)) {
        $interval = $record['interval'];
    }
       
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>UserJobs Green Apple Mail</title>
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
  var days = <?php if ($interval != NULL) echo $interval->d; else echo '0';?>  
  var hours = <?php if ($interval != NULL) echo $interval->h; else echo '0'?>  
  var minutes = <?php if ($interval != NULL) echo $interval->i; else echo '0'?>  
  var seconds = <?php if ($interval != NULL) echo $interval->s; else echo '0'?> 
  var timerVar = <?php if ($interval != NULL) echo $record['timer']; else echo '0'?> 
  
  
function popupnow(url) {
	newwindow=window.open(url,'name','height=200,width=400,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}


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

// -->

</script>
</head>

<body onload=<?php if ($interval != NULL) { ?>setCountDown(); <?php } ?>>
<div align="center">
  <table width="841" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td width="841" height="63" valign="top"><table width="840" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
        <!--DWLayoutTable-->
        <tr>
          <td width="79" height="22" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>ORDER</strong></td>
            <td width="140" align="center" valign="middle" bgcolor="#F0F0FF" class="style12"><strong>PROJECT NAME </strong></td>
            <td width="100" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>PAYMENT</strong></span></td>
            <td width="84" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>FOLDER</strong></span></td>
            <td width="217" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>STATUS </strong></span></td>
            <td width="148" align="center" valign="middle" bgcolor="#F0F0FF"><div align="center"><span class="style12"><strong>TIME LEFT AUTO DELETE </strong></span></div></td>
            <td width="56" align="center" valign="middle" bgcolor="#F0F0FF"><span class="style12"><strong>DELETE </strong></span></td>
          </tr>
        <tr>
          <td height="40" align="center" valign="middle" class="style12"><?php echo $record['orderNo']; ?></td>
            <td align="center" valign="middle"><input name="item_number" type="text" class="style12" id="item_number" value=<?php echo $record['project']; ?> size="25" maxlength="15" readonly=""  style="border:none"/></td>
            <td align="center" valign="middle">
                <?php
                    if ($record['paymentstatusString'] == Constants::ORDER_PAYMENT_STATUS_PENDING_STRING){ ?>
                        <a target="_top" href="../securezone/payments.php?orderid=<?php echo $record['orderid']; ?>&clientid=<?php echo $record['clientid']; ?>&from=documentsPendingLink" class="style1212"><?php echo $record['paymentstatusString']; ?></a>
                    <?php
                    }
                    else{?>
                        <input name="payment" type="text" class="style12" id="payment" value=<?php echo $record['paymentstatusString']; ?> size="15" maxlength="10" readonly=""  style="text-align:center; border:none"/>
                    <?php
                    }
                ?>
            </td>
            <td align="center" valign="middle"><a href="../securezone/userfiles.php" onclick="return popupnow('../securezone/userfiles.php?orderid=<?php echo $record['orderid']; ?>&clientid=<?php echo $record['clientid']; ?>&showDemo=<?php echo $record['showDemo']; ?>&showApple=<?php echo $record['showApple']; ?>')"
	><img src="../Images/FolderImage.png" width="34" height="22" border="0" /></a></td>
            <?php if ($record['status'] == "0" || $record['status'] == "1" || $record['status'] == "2" || $record['status'] == "3"){ ?>
                <td><img src=<?php echo '../Images/Status'.$record['status'].'.gif'; ?>  width="219" height="40" /></td>
            <?php } 
            else if ($record['status'] == "1B"){ ?>
                <td><img src=<?php echo '../Images/Status1.gif'; ?>  width="219" height="40" /></td>
             <?php } 
            else {  ?>
                <td><img src=<?php echo '../Images/Status4.gif'; ?>  width="219" height="40" /></td>
            <?php } ?>
            
            <td align="center" valign="middle" class="style12">
            <div id="interactivetimer">
                <?php if ($record['timer'] == 1){
                    echo $interval->d." D : ".$interval->h.":".$interval->i.":".$interval->s."";
                }
                ?>
           </div>
            </td>                
            
            <td align="center" valign="middle">
                <?php 
                if ($record['showDelete'] == 1){  ?>
                    <a onclick="return popupnow('../securezone/WarningMessage.php?orderid=<?php echo $record['orderid']; ?>&clientid=<?php echo $record['clientid']; ?>&from=documents')"><img src="../Images/DeleteRound.png" width="22" height="22" border="0" /></a>  
                 <?php  }
                ?>
                 </td>
          </tr>
        
        
        
        
        
        
        
        
        
        
      </table></td>



    <tr>
      <td height="3"></td>
    </tr>
  </table>
</div>
</body>
</html>

<?php
$_SESSION['$allProjectsCtr'] += 1;
}
else{
    header("location: ../login.php");
}
?>