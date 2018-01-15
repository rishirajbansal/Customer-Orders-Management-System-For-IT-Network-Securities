<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Accounting.php';
include_once '../functions.php';

if (session_id() == "") 
    session_start();

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $accounting = new Accounting();
    
    if ((isset($_POST['accounting_x']) && isset($_POST['accounting_y']))
            || (isset($_POST['accountingGotopage_x']) && isset($_POST['accountingGotopage_y']) )
            ||  (isset($_POST['accountingExcelReport_x']) && isset($_POST['accountingExcelReport_y']))
            || ( isset($_POST['pageFlag']) && ($_POST['pageFlag'] == 'first' || $_POST['pageFlag'] == 'next' || $_POST['pageFlag'] == 'last') ) ){  
        preventSQLInjection($accounting);
        
        if($action['result'] != 'error'){
        
            if ($accounting->getOptionType() == "1"){
                if (isset($_POST['accountingExcelReport_x']) && isset($_POST['accountingExcelReport_y'])){
                    $accounting->fetchCustomerOrdersBasedOnId(TRUE);
                    $reportData = $accounting->getReportData();
                    $_SESSION['accountingXLS'] = $reportData;
                    header('location: ../Admin/downloadReport.php?report=accounting&type=1');
                }
                else{
                    if (isset($_POST['accounting_x']) && isset($_POST['accounting_y'])) {
                        $accounting->setPageCount(1);
                    }
                    $accounting->fetchCustomerOrdersBasedOnId(FALSE);
                }
            }
            else if ($accounting->getOptionType() == "2"){
                if (isset($_POST['accountingExcelReport_x']) && isset($_POST['accountingExcelReport_y'])){
                    $accounting->fetchAllCustomerOrders(TRUE);
                    $reportData = $accounting->getReportData();
                     $_SESSION['accountingXLS'] = $reportData;
                    header('location: ../Admin/downloadReport.php?report=accounting&type=2');
                }
                else{
                     if (isset($_POST['accounting_x']) && isset($_POST['accounting_y'])) {
                        $accounting->setPageCount(1);
                    }
                    $accounting->fetchAllCustomerOrders(FALSE);
                }
            }
            else if ($accounting->getOptionType() == "3"){
                if (isset($_POST['accountingExcelReport_x']) && isset($_POST['accountingExcelReport_y'])){
                    $accounting->fetchCustomerOrdersBasedOnCreditAvail(TRUE);
                    $reportData = $accounting->getReportData();
                     $_SESSION['accountingXLS'] = $reportData;
                    header('location: ../Admin/downloadReport.php?report=accounting&type=3');
                }
                else{
                     if (isset($_POST['accounting_x']) && isset($_POST['accounting_y'])) {
                        $accounting->setPageCount(1);
                    }
                    $accounting->fetchCustomerOrdersBasedOnCreditAvail(FALSE);
                }
            }
        }
        $_SESSION['accounting'] = $accounting;
        
        $action['text'] = $text; 
    }
           
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - ACCOUNTING</title>
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
</head>

<body onload="MM_preloadImages('../Images/AdminImages/GObuttonON.jpg')">
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
  <table width="1000" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td height="104" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1000" height="104" valign="top"><iframe src="../Admin/adminHeader.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="../Images/AdminImages/GAMPageAdminAccounting.jpg" width="385" height="60" /></td>
          </tr>
      </table></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="615" height="60" valign="top"><iframe src="../Admin/adminSubmenu.php" width="615" height="60" frameborder="0" scrolling="no" ></iframe></td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td height="36" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="36" valign="top"><img src="../Images/AdminImages/GAMUAdminLonBarLEFT.jpg" width="385" height="36" /></td>
            <td width="615" align="right" valign="middle"  background="../Images/AdminImages/GAMUAdminLonBarRIGHT.jpg"  style="background-position:left"><span class="style105"><?php echo date('l F d Y \T\I\M\E: h:i A') ?></span> </td>
          </tr>
        
      </table></td>
    </tr>
    <tr>
      <td width="21" height="10"></td>
      <td colspan="2" rowspan="3" valign="top">
          <form id="ACCOUNTINGform" name="ACCOUNTINGform" method="post" action="">
              <input type="hidden" name="pageFlag" id="pageFlag"/>
              <input type="hidden" name="totalPages" id="totalPages" value="<?php echo $accounting->getTotalPages(); ?>"/>
              <input type="hidden" name="pageCount" id="pageCount" value="<?php echo $accounting->getPageCount(); ?>"/>
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <?php
         if ($message){ ?>
             <tr>
                 <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo $message; ?> 
                 </p></td>
             </tr>
          <?php }
          if(!empty($action['result']) && $action['result'] == 'error'){  ?>
            <tr>
                 <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo show_errors($action); ?> 
                 </p></td>
             </tr>
        <?php  }
        ?>
        <tr>
          <td width="958" height="44" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="230" height="44" align="left" valign="middle" bgcolor="#FFFFFF">
                <label>
                  <select name="SortAccounting" class="style1" id="SortAccounting">
                    <option value="0" selected="selected">*** SELECT REPORT ***</option>
                    <option value="1" <?php if ($accounting->getOptionType() == "1") echo 'selected="selected"'; ?> >Customer ID</option>
                    <option value="2" <?php if ($accounting->getOptionType() == "2") echo 'selected="selected"'; ?> >All Customers</option>
                    <option value="3" <?php if ($accounting->getOptionType() == "3") echo 'selected="selected"'; ?> >Credit Available</option>
                    </select>
                    </label>                  </td>
                  <td width="47" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Id : </td>
                  <td width="178" align="center" valign="middle" bgcolor="#FFFFFF"><input name="AccIDnumber" type="number" class="style1" id="AccIDnumber" value="<?php echo $accounting->getFilterCustomerId(); ?>" size="18" /></td>
                  <td width="105" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Date range [mm/dd/yyyy]: </td>
                  <td width="150" align="left" valign="middle" bgcolor="#FFFFFF"><input name="Rangefrom" type="text" class="style1" id="Rangefrom" value="<?php echo $accounting->getFilterDateFrom(); ?>" size="18" /></td>
                  <td width="41" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">to :</td>
                  <td width="150" align="left" valign="middle" bgcolor="#FFFFFF">
                      <label></label>                  <input name="Rangeto" type="text" class="style1" id="Rangeto" value="<?php echo $accounting->getFilterDateTo() ?>" size="18" /></td>
                  <td width="57" align="center" valign="middle" bgcolor="#FFFFFF"><input name="accounting" type="image" src="../Images/AdminImages/GObuttonOFF.jpg" width="34" height="34" id="Image12" value="Submit" onmouseover="MM_swapImage('Image12','','../Images/AdminImages/GObuttonON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                </tr>
            
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="40" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="118" height="40" valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td width="174" align="center" valign="middle" bgcolor="#FFFFFF" class="style1"><a href="javascript:first()" class="style1" <?php if ($accounting->getPageCount() == 1 || $accounting->getPageCount() == 0) { ?> onclick="javascript:return false;" <?php } ?>><?php echo 'First ['.$accounting->getPageCount().'/'.$accounting->getTotalPages().']' ?></a> </td>
                  <td width="100" align="center" valign="middle" bgcolor="#FFFFFF" class="style1"><a href="javascript:next()" class="style1" <?php if ($accounting->getPageCount() == $accounting->getTotalPages()) { ?> onclick="javascript:return false;" <?php } ?>>Next</a> </td>
                  <td width="100" align="center" valign="middle" bgcolor="#FFFFFF" class="style1"><a href="javascript:last()" class="style1" <?php if ($accounting->getPageCount() == $accounting->getTotalPages()) { ?> onclick="javascript:return false;" <?php } ?>>Last</a> </td>
                  <td width="105" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Go to page: </td>
                  <td width="88" align="left" valign="middle" bgcolor="#FFFFFF" class="style1"><input name="GoPageCusto" type="text" class="style1" id="GoPageCusto" size="8" value="" /></td>
                  <td width="55" align="center" valign="middle" bgcolor="#FFFFFF"><input name="accountingGotopage" type="image" src="../Images/AdminImages/GObuttonOFF.jpg" width="34" height="34" id="Image13" value="Submit" onmouseover="MM_swapImage('Image13','','../Images/AdminImages/GObuttonON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                  <td width="76" valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td width="200" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Export report to excel: </td>
                  <td width="57" align="center" valign="middle" bgcolor="#FFFFFF"><input name="accountingExcelReport" type="image" src="../Images/AdminImages/ExcelButtonOFF.png" width="34" height="34" id="Image15" value="Submit" onmouseover="MM_swapImage('Image15','','../Images/AdminImages/ExcelButtonON.png',1)" onmouseout="MM_swapImgRestore()" /></td>
                  <td width="85" valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
                </tr>
            
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="499" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="958" height="47" valign="top" bgcolor="#FFFFFF" ><img src="../Images/Bartopbottom.jpg" width="958" height="47" /></td>
                </tr>
            <tr>
              <td height="405" align="center" valign="middle" bgcolor="#222641" ><iframe src="../Admin/AccountingCustomerTable.php" width="900" height="400" frameborder="0" scrolling="yes" ></iframe></td>
                </tr>
            <tr>
              
              <td height="47" valign="top" ><img src="../Images/Bartopbottom2.jpg" width="958" height="47" /></td>
                </tr>
            
            </table></td>
          </tr>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </table>
          </form>
      </td>
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
      <td height="346"></td>
      <td></td>
    </tr>
    <tr>
      <td height="9"></td>
      <td width="364"></td>
      <td width="594"></td>
      <td></td>
    </tr>
  </table>
</div>
</body>
    
<script>
    function first(){
        document.getElementById('pageFlag').value = "first"; 
        document.ACCOUNTINGform.submit();
    }
    
    function next(){
        document.getElementById('pageFlag').value = "next";
        document.ACCOUNTINGform.submit();
    }
    
    function last(){
        document.getElementById('pageFlag').value = "last"; 
        document.ACCOUNTINGform.submit();
    }
</script>
</html>

<?php
}
else{
    header("location: ../login.php");
}

function preventSQLInjection(Accounting $accounting){
     global $action, $text;
     
    $optionType = mysql_real_escape_string($_POST['SortAccounting']);    
    $filterCustomerId = mysql_real_escape_string($_POST['AccIDnumber']);
    $filterDateFrom = mysql_real_escape_string($_POST['Rangefrom']);
    $filterDateTo = mysql_real_escape_string($_POST['Rangeto']);
    $totalPages = mysql_real_escape_string($_POST['totalPages']);
    $pageFlag = mysql_real_escape_string($_POST['pageFlag']);
    
    if ($optionType == "1" && empty($filterCustomerId)){
        $action['result'] = 'error'; array_push($text,'Please enter Customer id');
    }
    
    if (isset($_POST['GoPageCusto']) && !empty($_POST['GoPageCusto'])){
        $pageCount = mysql_real_escape_string($_POST['GoPageCusto']);
        $accounting->setPageCount($pageCount);
    }
    else{
        $pageCount = mysql_real_escape_string($_POST['pageCount']);
        $accounting->setPageCount($pageCount);
    }
    
    $accounting->setOptionType($optionType);
    $accounting->setFilterCustomerId($filterCustomerId);
    $accounting->setFilterDateFrom($filterDateFrom);
    $accounting->setFilterDateTo($filterDateTo);
    $accounting->setPageFlag($pageFlag);
    $accounting->setTotalPages($totalPages);
}


?>