<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Customers.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';

if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $customersList = NULL ;
    
    if (!empty($_SESSION['customers'])){
        $customers = $_SESSION['customers'];
        
        $customersList = $customers->getAllCustomers();  
        
        
        unset($_SESSION['customers']);
    }
   
           
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Customer Table</title>
<link type="text/css" rel="stylesheet" href="../css/form.css">
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
function popitup(url) {
	newwindow=window.open(url,'name','height=200,width=300,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
</head>

<body>

<div align="left">
    <?php
    if (!$customersList) { ?>
        <center><strong>No Records</strong></center>
    <?php } else { ?>
        
  <table width="1775"  height="520" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td width="1775" height="469" align="left" valign="top" ><table width="1775" border="1" align="center" cellpadding="0" cellspacing="0" >
          <tr  align="center" class="style1">
            <td width="23" height="35"><strong>#</strong></td>
            <td width="107"><strong>Customer ID</strong> </td>
            <td width="78" ><strong>First name</strong> </td>
            <td width="74" ><strong>Last name</strong></td>
            <td width="148" ><strong>Company</strong></td>
            <td width="174" ><strong>Email</strong></td>
            <td width="247"><strong>Address 1</strong></td>
            <td width="137"><strong>Address 2</strong></td>
            <td width="144"><strong>Country</strong></td>
            <td width="119" ><strong>City</strong></td>
            <td width="100"><strong>State</strong></td>
            <td width="114" ><strong>Zip Code</strong></td>
            <td width="141" ><strong>Phone</strong></td>
            <td width="56" ><strong>Edit</strong></td>
            <td width="65" ><strong>Orders</strong></td>
            <td width="48" ><strong>#</strong></td>
          </tr>
           
          <?php
                $ctr = 0;
                if ($customers->getPageCount() == 1){
                    $ctr = 1;
                }
                else{
                    $ctr = (($customers->getPageCount() - 1) * Config::$customersMaxCustomerOnPage) + 1;
                }
                
              foreach ($customersList as $custRecord) {
                  
                  //$ctr = $custRecord['ctr'];
                  $customerid = $custRecord['customerid'];
                  $fname = $custRecord['fname'];
                  $lname = $custRecord['lname'];
                  $company = $custRecord['company'];
                  $email = $custRecord['email'];
                  $address1 = $custRecord['address1'];
                  $address2 = $custRecord['address2'];
                  $country = $custRecord['country'];
                  $city = $custRecord['city'];
                  $state = $custRecord['state'];
                  $zipcode = $custRecord['zipcode'];
                  $phone = $custRecord['phone'];
                  
                  ?>
                  <tr  align="center" class="style1">
                    <td height="35"><?php echo $ctr; ?></td>
                    <td><?php echo $customerid; ?></td>
                    <td><?php echo $fname; ?></td>
                    <td><?php echo $lname; ?></td>
                    <td><?php echo $company; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $address1; ?> </td>
                    <td><?php echo $address2; ?> </td>
                    <td><?php echo $country; ?> </td>
                    <td><?php echo $city; ?> </td>
                    <td><?php echo $state; ?> </td>
                    <td><?php echo $zipcode; ?> </td>
                    <td><?php echo $phone; ?></td>
                    <td><a href="admincustomersprofile.php?clientid=<?php echo $customerid; ?>" target="_top"><img src="../Images/AdminImages/EditButton.png" width="34" height="22" border="0" /></a></td>
                    <td><a href="adminCustomerOrders.php?clientid=<?php echo $customerid; ?>" target="_top"><img src="../Images/FolderImage.png" width="34" height="22" border="0" /></a></td>
                    <td><?php echo $ctr; ?></td>
                  </tr>
                  
             <?php $ctr+=1; }
          ?>
          
      </table></td>
    </tr>
    <tr>
      <td height="36" >&nbsp;</td>
    </tr>
  </table>
  <?php } ?>
</div>
</body>
</html>

<?php
}
else{
    header("location: ../login.php");
}


?>