<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
$order = new Order();
    if (isset($_GET['orderid']) && isset($_GET['clientid'])){
        
        
        $order->setOrderNo($_GET['orderid']);
        $order->setClientid($_GET['clientid']);
        
        $order->downloadUserDataFiles();
    }

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>User Files Green Apple Mail</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url();
	background-repeat: no-repeat;
	background-color: #FFFFFF;
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
function popupnow(ftp) {
	newwindow=window.open(ftp,'name','height=200,width=300,screenX=600,screenY=300');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head>

<body>
<div align="center">
  <table width="400" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <?php
        if ($order->getMessage()){ ?>
            <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo $order->getMessage(); ?> 
                </p></td>
            </tr>
         <?php }
       ?>
    <tr>
      <td width="400" height="200" valign="top" bgcolor="#FFFFFF"><table width="400" border="0" cellspacing="0" cellpadding="0">
        <!--DWLayoutTable-->
        <?php 
        if ($order->getCleanFiles() && $_GET['showApple'] == 1){
            $ctr = 1; ?>
            <tr>  <?php
            foreach ($order->getCleanFiles() as $filename) { ?>
                
                    <td width="133" height="100" align="center" valign="middle"><a href="<?php echo $filename; ?>" target="_blank"><img src="../Images/AppleMailFileImage.jpg" width="60" height="60" border="0" /></a><br />
                      <span class="style12">File Processed <?php echo $ctr; ?></span></td>
                
            <?php ++$ctr; }
            ?> </tr> <?php
        }
        ?>
        <?php 
        if ($order->getDemoFiles() && $_GET['showDemo'] == 1){
            $ctr = 1; ?>
            <tr>  <?php
            foreach ($order->getDemoFiles() as $filename) { ?>
                
                    <td width="133" align="center" valign="middle"><a href="<?php echo $filename; ?>"><img src="../Images/FolderDEMOfile.png" width="34" height="22" border="0" /></a><br />
                        <span class="style12">Demo File <?php echo $ctr; ?></span> </td>
                
            <?php ++$ctr; }
            ?> </tr> <?php
        }
        ?>
        <?php 
        if ($order->getPdfFiles()){
            $ctr = 1; ?>
            <tr>  <?php
            foreach ($order->getPdfFiles() as $filename) { ?>

                    <td width="133" height="100" align="center" valign="middle"><a href="<?php echo $filename; ?>" target="_blank"><img src="../Images/AcrobatFileImage.png" width="48" height="50" border="0" /></a><br />
                         <span class="style12">Receipt File <?php echo $ctr; ?> </span></td>
          
            <?php ++$ctr; }
            ?> </tr> <?php
        }
        ?>
          <tr>
            <td width="134" align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        
        
        <tr>
          <td height="100" align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        
        
        
        
            </table></td>
    </tr>
  </table>
</div>
</body>
</html>
