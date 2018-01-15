<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'AdminStats.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $adminStats = new AdminStats();
    
    $adminStats->fetchStatsData();
       
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - STATS</title>
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

</head>

<body>
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
          <td width="385" height="60" valign="top"><img src="../Images/AdminImages/GAMPageAdminStats.jpg" width="385" height="60" /></td>
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
      <td colspan="2" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="558" height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Total Subscribed Customers:</td>
            <td width="400" align="left" valign="middle" bgcolor="#FFFFFF" class="style1" >
              <label>
                  <input name="TotalCustomers" type="text" class="style1"  style="border:hidden;" value="<?php echo $adminStats->getTotalSubscribedCustomers(); ?>" />
              </label>          </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 0 - Pending for payment : </td>
            <td align="left" valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalPending" type="text" class="style1"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_0_paymentPending(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 0 - Pending for payment older than 30 days : </td>
            <td align="left" valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalPending30days" type="text" class="style1" id="TotalPending30days"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_0_paymentPendingFrom30Days(); ?>" />
            </label>       </td>
          </tr>
       
       
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 1 - Paid : </td>
            <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrdersStat1" type="text" class="style1" id="TotalOrdersStat1"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_1_paid(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 2 - Approving documents : </td>
            <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderStat2" type="text" class="style1" id="TotalOrderStat2"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_2_approving(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 3 - Order in process : </td>
            <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderStat3" type="text" class="style1" id="TotalOrderStat3"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_3_processing(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 4 - Done - 20 days left : </td>
            <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderStat4" type="text" class="style1" id="TotalOrderStat4"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_4_done_20daysleft(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 5 - Done - 10 days left :</td>
            <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderStat5" type="text" class="style1" id="TotalOrderStat5"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_5_done_10daysleft(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 6 - Done - 5 days left :</td>
           <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                   <input name="TotalOrderStat6" type="text" class="style1" id="TotalOrderStat6"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_6_done_5daysleft(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 7 - Done - 1 days left :</td>
           <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                   <input name="TotalOrderStat7" type="text" class="style1" id="TotalOrderStat7"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_7_done_1dayleft(); ?>" />
            </label>       </td>
          </tr>
        
        
            <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Customers with CREDIT Available : </td>
              <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                      <input name="TotalCustomersCreditOpen" type="text" class="style1" id="TotalCustomersCreditOpen"  style="border:hidden;" value="<?php echo $adminStats->getTotalCreditAvailableCustomers(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Amount of Credit Available in Customers :</td>
            <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotarsCreditamount" type="text" class="style1" id="TotarsCreditamount"  style="border:hidden;" value="<?php echo $adminStats->getTotalCreditAmout(); ?>" />
            </label>       </td>
          </tr>
		   <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 1 - With promotion type B : </td>
            <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderPromoB" type="text" class="style1" id="TotalOrderPromoB"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_1B_promotionB_paymentPending(); ?>" />
            </label>       </td>
          </tr>
		  
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 4A  - Done - Promotion type B - 20 days left :</td>
            <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderStat4A" type="text" class="style1" id="TotalOrderStat4A"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_4A_promotionB_done_20daysleft(); ?>" />
            </label>       </td>
          </tr>
		  <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 5B - Done - 10 days left :</td>
            <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                    <input name="TotalOrderStat5B" type="text" class="style1" id="TotalOrderStat5B"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_5B_promotionB_done_10daysleft(); ?>"" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 6B - Done - 5 days left :</td>
           <td align="left"  valign="middle" bgcolor="#FFFFFF" ><label>
                   <input name="TotalOrderStat6B" type="text" class="style1" id="TotalOrderStat6B"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_6B_promotionB_done_5daysleft(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" >Total Orders status 7B - Done - 1 days left :</td>
           <td align="left"   valign="middle" bgcolor="#FFFFFF" ><label>
                   <input name="TotalOrderStat7B" type="text" class="style1" id="TotalOrderStat7B"  style="border:hidden;" value="<?php echo $adminStats->getTotalOrderStatus_7B_promotionB_done_1dayleft(); ?>" />
            </label>       </td>
          </tr>
        <tr>
          <td height="30" align="right" valign="middle" bgcolor="#FFFFFF" class="style1" ><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td valign="middle" bgcolor="#FFFFFF" ><label></label>       </td>
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
      <td height="303"></td>
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