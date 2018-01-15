<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once '../functions.php';

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'CustomersOrders.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;;

if (isset($_POST['ADMINLOADERGOsubmit_x']) && isset($_POST['ADMINLOADERGOsubmit_y'])){
    
    $customerOrders = new CustomersOrders();
    
    $orderid = $_POST['orderid'];
    $clientid = $_POST['clientid'];
    $selectedOption = $_POST['select'];
    $file = isset($_FILES['filename']) ? $_FILES['filename']['name'] : "";
    
    if(empty($selectedOption) || $selectedOption == "0"){ $action['result'] = 'error'; array_push($text,'Please select an option'); }
    if(empty($file)){ $action['result'] = 'error'; array_push($text,'Please select file'); }
    
    if($action['result'] != 'error'){
        $orderid = $_POST['orderid'];
        $clientid = $_POST['clientid'];
        $selectedOption = $_POST['select'];

        $flag = $customerOrders->updateCustomerFolder($orderid, $clientid, 'LOAD' , $selectedOption );
        if ($flag == 0){
            $message = "Failed to find customer dir : ".$orderid.'-'.$clientid;
        }
        else if ($flag == 1){
            $message = "Failed to upload file to customer folder";
        }
        else if ($flag == 2){
            $message = "File is uploaded successfully to customer folder.";
        }
    }
    
    $action['text'] = $text;
    
}
else if (isset($_POST['WarningMessage_x']) && isset($_POST['WarningMessage_y'])){
    
    $customerOrders = new CustomersOrders();
    
    $orderid = $_POST['orderid'];
    $clientid = $_POST['clientid'];
    $selectedOption = isset($_POST['select']) ? $_POST['select'] : "" ;
    
    if(empty($selectedOption) || $selectedOption == "0"){ $action['result'] = 'error'; array_push($text,'Please select file type to delete from selection box.'); }
    
    if($action['result'] != 'error'){
        $flag = $customerOrders->updateCustomerFolder($orderid, $clientid, 'DELETE' , $selectedOption );
        if ($flag == 0){
            $message = "Failed to find customer dir : ".$orderid.'-'.$clientid;
        }
        else {
            $message = "File is deleted successfully from customer folder.";
        }
    }
    
    $action['text'] = $text;
    
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Loader</title>
<link type="text/css" rel="stylesheet" href="../css/userjobs.css">
<!--<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
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
function popupnow(url) {
        url = url + '&select=' + document.getElementById('select').value;
	newwindow=window.open(url,'_parent','height=200,width=400,screenX=600,screenY=300,toolbar=no,resizable=no,titlebar=no,scrollbars=no,menubar=no,status=no');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
</head>

<body onload="MM_preloadImages('../Images/AdminImages/GObuttonON.jpg')">
<div align="center">
    <form id="ADMINLOADERform" name="ADMINLOADERform" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="orderid" id="orderid" value="<?php if (isset($_GET['orderid'])) echo $_GET['orderid']; else echo $_POST['orderid']; ?>"/>
        <input type="hidden" name="clientid" id="clientid" value="<?php if (isset($_GET['clientid'])) echo $_GET['clientid']; else echo $_POST['clientid']; ?>"/>
  <table width="674" border="0" cellpadding="0" cellspacing="0">
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
    <tr>
      <td width="674" height="34" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="157" height="34" align="center" valign="middle">
            <label>
          <select name="select" id="select" class="TypeOfElement">
            <option value="0" >ADD ELEMENT</option>
            <option value="1" >CLEAN FILE</option>
            <option value="2" >DEMO FILE</option>
            <option value="3" >PDF FILE</option>
          </select>
            </label>      </td>
      <td width="418" align="center" valign="middle">
        <label>
          <input name="filename" id="filename"  type="file" class="style1" size="50" />
          </label>      </td>
      <td width="61" align="center" valign="middle" class="style12"><input name="ADMINLOADERGOsubmit" type="image" src="../Images/AdminImages/GObuttonOFF.jpg" width="34" height="34" id="Image17" value="Submit" onmouseover="MM_swapImage('Image17','','../Images/AdminImages/GObuttonON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
    <td width="38" align="center" valign="middle" ><a onclick="return popupnow('../securezone/WarningMessage.php?orderid=<?php if (isset($_GET['orderid'])) echo $_GET['orderid']; else echo $_POST['orderid']; ?>&clientid=<?php if (isset($_GET['clientid'])) echo $_GET['clientid']; else echo $_POST['clientid']; ?>&from=adminLoader')"><img src="../Images/DeleteRound.png" width="22" height="22" border="0" /></a></td>
    </tr>
      </table>      </td>
    </tr>
      
  </table>
    </form>
</div>
</body>
</html>
