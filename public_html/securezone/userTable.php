<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'UserOrder.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'DatabaseConnectionManager.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'DatabaseQueryManager.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    
    $order = new Order();
    
    $resultCancelledOrders = $order->fetchOrderCancelled($_SESSION['email']);
    
    $resultAllOrders = $order->fetchOrderAll($_SESSION['email']);
    
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>UserTable Green Apple Mail</title>
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
<div align="center">
  <table width="841"  height="520" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <?php 
        if ($resultCancelledOrders){
            $cancelledProjects = $order->getCancelledProjects();  
            $_SESSION['$cancelledProjects'] = $cancelledProjects;
            $_SESSION['$cancelledProjectsCtr'] = 1;
            foreach ($cancelledProjects as $record) {
                ?>
                <tr>
                    <td width="841" height="67" valign="top"><iframe src="../securezone/userCredit.php" width="841" height="67" frameborder="0" scrolling="no" ></iframe></td>
                  </tr>                        
             <?php
            }
        }
    ?>
    
    <?php 
        if ($resultAllOrders){
            $allProjects = $order->getAllProjects();  
            $_SESSION['$allProjects'] = $allProjects;
            $_SESSION['$allProjectsCtr'] = 1;
            foreach ($allProjects as $record) {
                ?>
                <tr>
                    <td height="67" valign="top"><iframe src="../securezone/userJobs.php" width="841" height="67" frameborder="0" scrolling="no" ></iframe></td>
                </tr>                    
             <?php
            }
        }
    ?>
  </table>
</div>
</body>
</html>

<?php
//unset($_SESSION['record']);
}
else{
    header("location: ../login.php");
}
?>