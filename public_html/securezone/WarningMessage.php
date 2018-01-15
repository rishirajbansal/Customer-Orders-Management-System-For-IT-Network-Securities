<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Warning Message Green Apple Mail</title>
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
function popupnow(url) {
	newwindow=window.open(url,'name','height=200,width=400,screenX=600,screenY=300');
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
//-->
</script>


</head>

<body onload="MM_preloadImages('../Images/WarningMssg/DeleteWarningYESON.png','../Images/WarningMssg/DeleteWarningCANCELON.png')">
<div align="center">
    
    <?php
        if (isset($_GET['message'])){ ?>
            <tr>
                <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo $_GET['message']; ?> 
                </p></td>
            </tr>
         <?php }
         else{ 
       ?>

        <form id="WarningMessageform" name="WarningMessageform" method="post" action="<?php if ($_GET['from'] == 'COCasePAID') { ?>../Admin/COCasePAID.php <?php } else if ($_GET['from'] == 'COCasePromoB') {?> ../Admin/COCasePromoB.php <?php } else if ($_GET['from'] == 'COCredit') {?> ../Admin/COCredit.php <?php } else if ($_GET['from'] == 'adminLoader') {?> ../Admin/adminLoader.php <?php } else if ($_GET['from'] == 'documents') {?> ../securezone/documents.php <?php } else if ($_GET['from'] == 'admincustomersprofile') {?> ../Admin/admincustomers.php <?php }?> ">
            <input type="hidden" name="orderid" id="orderid" value="<?php if (isset($_GET['orderid'])) echo $_GET['orderid']; ?>"/>
           <input type="hidden" name="counter" id="counter" value="<?php if (isset($_GET['counter'])) echo $_GET['counter']; ?>"/>
           <input type="hidden" name="clientid" id="clientid" value="<?php if (isset($_GET['clientid'])) echo $_GET['clientid']; ?>"/>
           <input type="hidden" name="select" id="select" value="<?php if (isset($_GET['select'])) echo $_GET['select']; ?>"/>
      <table width="400" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="143" colspan="2" valign="top"><img src="../Images/WarningMssg/DeleteWarningTOP.png" width="400" height="143" /></td>
        <tr>
          <td width="201" height="57" valign="top"><img src="../Images/WarningMssg/DeleteWarningCANCELOFF.png" width="201" height="57" id="Image1" onmouseover="MM_swapImage('Image1','','../Images/WarningMssg/DeleteWarningCANCELON.png',1)" onmouseout="MM_swapImgRestore()" onclick="window.close()" /></td>
          <td width="201" valign="top"><input name="WarningMessage" type="image"  src="../Images/WarningMssg/DeleteWarningYESOFF.png" width="199" height="57" id="Image2" value="Submit" onmouseover="MM_swapImage('Image2','','../Images/WarningMssg/DeleteWarningYESON.png',1)" onmouseout="MM_swapImgRestore() "  /></td>
        </table>
    </form>        
    
     <?php }
        ?>
</div>
</body>

    
</html>
