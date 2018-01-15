<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once '../functions.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'UserOrder.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Promotion.php';


if (session_id() == "") 
    session_start();

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    
    $order = new Order();
    
    if (isset($_POST['orders_x']) && isset($_POST['orders_y'])){
        
        preventSQLInjectionAndValidate($order);
        
        if($action['result'] != 'error'){
            $profile = new Profile();
            
            $result = $profile->fetchBasedOnEmail($_SESSION['email']);
            
            if ($result){
                $result = $order->saveOrder($profile);
                
                if ($result){
                    $result = $order->uploadDataFileToFTP($profile);
                    
                    if ($result){
                        $promotion = new Promotion();
                        $promodetail = $promotion->isPromotionApplicable($order->getNoOfRecords());
                                                
                        $action['result'] = 'success';
                        $_SESSION['userOrder'] = $order;
                        $_SESSION['userOrder_promodetail'] = $promodetail;
                        
                        header("location: ../securezone/payments.php");
                    }
                    else{
                        $action['result'] = 'error'; 
                        array_push($text,'Sorry, failed to upload data file. Please try after some time. Reason: ' . $order->getMessage());
                    }
                }
                else{
                    $action['result'] = 'error';
                    array_push($text,'Sorry, failed to save project record. Please try after some time. Reason: ' . mysql_error());
                }
            }
            else{
                $action['result'] = 'error'; 
                array_push($text,'Sorry, failed to retrive your record. Please try after some time.');
            }
        }
        
        $action['text'] = $text;
    }

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - Orders</title>
<link type="text/css" rel="stylesheet" href="../css/GAMmain.css">
<script type="text/JavaScript" src="../js/common.js"></script>
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
-->
</style>
<SCRIPT LANGUAGE="JavaScript">

var max_units = 9000000; 
var currency = "$";

function getDiscountPrice(units) {
if (units >= max_units) return 4.89;
if (units >= 2000000) return 4.93;
if (units >= 1500001) return 4.93; 
if (units >= 1500000) return 5.8;
if (units >= 1000001) return 5.8;
if (units >= 1000000) return 6.8;
if (units >= 750001) return 6.8;
if (units >= 750000) return 7.74;
if (units >= 500001) return 7.74;
if (units >= 500000) return 9.76;
if (units >= 250001) return 9.76;
if (units >= 250000) return 15.49;
if (units >= 75001) return 15.49;
if (units >= 75000) return 29.64;
if (units >= 30001) return 29.64;
if (units >= 30000) return 37.75;
if (units >= 20001) return 37.75;
if (units >= 20000) return 51.52;
if (units >= 10001) return 51.52;
if (units >= 10000) return 92.94;
if (units >= 101) return 92.94;
if (units == 0) return 0;
}

function getComodin(units) {
if (units >= max_units) return 999;
if (units >= 2000000) return 989;
if (units >= 1500001) return 989; 
if (units >= 1500000) return 840;
if (units >= 1000001) return 840;
if (units >= 1000000) return 715;
if (units >= 750001) return 715;
if (units >= 750000) return 619;
if (units >= 500001) return 619;
if (units >= 500000) return 513;
if (units >= 250001) return 513;
if (units >= 250000) return 369;
if (units >= 75001) return 369;
if (units >= 75000) return 230;
if (units >= 30001) return 230;
if (units >= 30000) return 170;
if (units >= 20001) return 170;
if (units >= 20000) return 137;
if (units >= 10001) return 137;
if (units >= 10000) return 90;
if (units >= 1000) return 90;
if (units >= 101) return 90;
if (units == 0) return 0;
}

function getNumberOfUnits() {
var units = document.calculator.units.value; 

return (units == "") ? 0 : units;
}

function findUnits(){
    document.calculator.units.value = number_format(document.calculator.units.value, '', '',',');
}

function showResult(result) {
document.calculator.amount.value = result;	
}

function formatMessage(units, unit_price, unit_coco) {
return currency + formatPrice((units * (unit_price/10000)) + unit_coco);
}

function contactMessage(units, unit_price, unit_coco) {
return "contact us";
}

function getAltUnits(units) {
var discount_price = getDiscountPrice(units);
if (units < max_units) do { units++ } while (discount_price == getDiscountPrice(units));
return units;
}

function findPrice() {
var units = getNumberOfUnits();
var unit_price = getDiscountPrice(units);
var alt_units = getAltUnits(units);
var unit_coco = getComodin(units);
var alt_unit_price = getDiscountPrice(alt_units);
var result;
if (units >= 2000001)
{result = contactMessage(units, unit_price,unit_coco);}
else if (units >= 101){
result = formatMessage(units, unit_price,unit_coco);} 
else
{result = (currency + 80.00);}
showResult(result);
}

function formatPrice(value) {
var result= Math.floor(value) + ".";
var cents = 10 * (value-Math.floor(value)) + 0.50;
result += Math.floor(cents / 10);
result += Math.floor(cents % 10);
return result;
}

function filterNonNumeric(field) {
var result = new String();
var numbers = "0123456789";
var chars = field.value.split("");  
for (i = 0; i < chars.length; i++) {
if (numbers.indexOf(chars[i]) != -1) result += chars[i];
}
if (field.value != result) field.value = result;
}

//Turnaround code

function getTurnaround(units) {
if (units >= max_units) return 9;
if (units >= 2000000) return 7; 
if (units >= 1000000) return 5;
if (units >= 850000) return 4;
if (units >= 450000) return 3;
if (units >= 200000) return 2;
if (units >= 50000) return 1;
if (units >= 1) return 1;
if (units == 0) return 1;
}

function showTURNResult(result) {
document.calculator.turnaround.value = result;	
}

function formatTURNMessage(units, turn_units) {
return getTurnaround(units)*1;
}

function contactTURNMessage(units, turn_units) {
return "contact us";
}

function findTURNPrice() {
var units = getNumberOfUnits();
var turn_units = getTurnaround(units);
var result;
if (units >= 2000001)
{result = contactTURNMessage(units, turn_units);}
else if (units >= 1){
result = formatTURNMessage(units, turn_units);} 
else
{result = (0);}
showTURNResult(result);
}

</script>


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

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body onload="MM_preloadImages('../Images/GAMButtonSaveON.jpg')">
<!-- Start of StatCounter Code for Dreamweaver -->
<script type="text/javascript">
var sc_project=8682132; 
var sc_invisible=1; 
var sc_security="f2b50c78"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="web analytics"
href="http://statcounter.com/" target="_blank"><img
class="statcounter"
src="http://c.statcounter.com/8682132/0/f2b50c78/1/"
alt="web analytics"></a></div></noscript>
<!-- End of StatCounter Code for Dreamweaver -->

<div align="center">
  <table width="1001" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td height="104" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1000" height="104" valign="top"><iframe src="../securezone/header.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
      <td width="1"></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="../Images/GAMPageTitleOrders.jpg" width="385" height="60" id="Image1" /></td>
          </tr>
      </table></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="615" height="60" valign="top"><iframe src="../securezone/submenu.php" width="615" height="60" frameborder="0" scrolling="no" ></iframe></td>
        </tr>
      </table>      </td>
      <td></td>
    </tr>
    <tr>
      <td height="36" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="241" height="36" valign="top"><img src="../Images/GAMUserLowBarPlaceOrderNOACTIVE.jpg" width="241" height="36" /></td>
            <td width="759" valign="top"><img src="../Images/GAMUserLowBarREST.jpg" width="759" height="36" /></td>
          </tr>
        
      </table></td>
      <td></td>
    </tr>
    <tr>
      <td width="21" height="10"></td>
      <td colspan="2" rowspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
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
          <td width="958" height="44" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">Place or retrieve your order here: <br />
            </p></td>
          </tr>        
        <tr>
          <td height="186" valign="top" ><form id="calculator" name=calculator  method="post" action="" enctype="multipart/form-data">
            <div align="center">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                <!--DWLayoutTable-->
                
                <tr>
                  <td width="958" height="216" valign="top">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          
                          <!--DWLayoutTable-->
                          <tr>
                            <td width="30%" height="39" align="right" valign="middle"><span class="style1">Project name : </span></td>
                            <td width="280" align="left" valign="middle" background="../Images/ArrowsForm.jpg"  style="background-repeat:no-repeat;" ><input name="item_number" type="text" class="style1" id="item_number" size="40" maxlength="15" /></td>
                            <td colspan="3" align="left" valign="middle" class="style12">Give a name or description to your project to help us locate your file and expedite our future communications. Max. 15 characters, with no spaces.</td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Volume of records :</span></td>
                            <td align="left" valign="middle" background="../Images/ArrowsForm.jpg" style="background-repeat:no-repeat;"><input name="units" type="text" class="style1" onkeypress="findPrice(); findTURNPrice();findUnits();" onkeydown="findPrice(); findTURNPrice();findUnits();" onkeyup="filterNonNumeric(this); findPrice(); findTURNPrice();findUnits();" value="0" size="40" maxlength="60"/></td>
                            <td colspan="3" align="left" valign="middle" class="style12">Number of emails in your file you want us to validate</td>
                          </tr>
                          
						  <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Total price :</span></td>
                            <td align="left" valign="middle"><input name="amount"  height="20" type="text" class="style1"  onfocus="This.blur()" size="40" maxlength="60"  readonly=""/></td>
                            <td colspan="3" align="left" valign="middle" class="style12">Amount expressed in US dollars. </td>
                          </tr>
                          <tr>
                            <td height="39" align="right" valign="middle"><span class="style1">Estimated processing time (days) : </span></td>
                            <td align="left" valign="middle"><input name="turnaround" type="text" height="20" class="style1"  onfocus="This.blur()" size="40" maxlength="60" readonly="" /></td>
                            <td colspan="3" align="left" valign="middle" class="style12">The process starts within 24 hours after we receive your payment and have checked your file layout and format. <br />
                            We will keep you posted via email about our progress. </td>
                          </tr>
						 
                          <tr>
                            <td height="56" align="right" valign="middle"><span class="style1">Locate your file in your computer :<br />
                            </span></td>
                            <td colspan="2" align="left" valign="middle" background="../Images/ArrowsFormFile.jpg" style="background-repeat:no-repeat;"><input name="filename" type="file" class="style1" id="filename" size="40" maxlength="60"   /></td>
							<td width="192" align="left" valign="middle" class="style12">Accepted file formats: excel, csv, txt</td>
                            <td width="103" rowspan="2" align="left" valign="top">
                              <label>
                              <input name="orders" type="image" id="Image10" onclick="MM_validateForm('item_number','','R','units','','R');return document.MM_returnValue" onmouseover="MM_swapImage('Image10','','../Images/GAMbuttonNextON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/GAMbuttonNextOFF.jpg" width="103" height="61" />
                            </label></td>
                          </tr>
                          <tr>
                            <td height="24">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="78">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="0"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                      </table></td>
                    </tr>
                  </table>
                </div>
            </form></td>
          </tr>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </table></td>
      <td width="21"></td>
      <td></td>
    </tr>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <tr>
      <td rowspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="21" height="135" valign="top"><img src="../Images/GAMLEFTspace.jpg" width="21" height="135" /></td>
          </tr>
        <tr>
          <td height="92">&nbsp;</td>
          </tr>
      </table></td>
      <td rowspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="21" height="143" valign="top"><img src="../Images/GAMRIGHT-space.jpg" width="21" height="143" /></td>
          </tr>
        <tr>
          <td height="84">&nbsp;</td>
          </tr>
        
        
      </table></td>
      <td height="220"></td>
    </tr>
    <tr>
      <td colspan="2" rowspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="958" height="160" valign="top"><iframe src="../securezone/footer.php" width="958" height="160" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
      <td height="7"></td>
    </tr>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <tr>
      <td height="153"></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td height="5"></td>
      <td width="364"></td>
      <td width="594"></td>
      <td></td>
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

function preventSQLInjectionAndValidate(Order $order){
    global $action, $text;
    
    $projectName = mysql_real_escape_string($_POST['item_number']);
    $noOfRecords = mysql_real_escape_string($_POST['units']);
    $totalPrice = mysql_real_escape_string($_POST['amount']);
    $processingTime = mysql_real_escape_string($_POST['turnaround']);
    
    if(empty($projectName)){ $action['result'] = 'error'; array_push($text,'Project Name is required'); }
    if(empty($noOfRecords)){ $action['result'] = 'error'; array_push($text,'No. or  records are required'); }
    if(empty($totalPrice)){ $action['result'] = 'error'; array_push($text,'Address is required'); }
    if(empty($processingTime)){ $action['result'] = 'error'; array_push($text,'Processing Time is required'); }
    if( $_FILES['filename']['name'] == "" ) { $action['result'] = 'error'; array_push($text,'File is required'); }
    
    if(!empty($noOfRecords)){$noOfRecords = str_replace(',', '', $noOfRecords);}
    
    $order->setProjectName($projectName);
    $order->setNoOfRecords($noOfRecords);
    $order->setProcessingTime($processingTime);
    $totalPrice = substr($totalPrice,1);
    $order->setTotalPrice($totalPrice);

}
    
?>