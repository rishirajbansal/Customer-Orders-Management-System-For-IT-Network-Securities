<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Credit Voucher</title>
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
//-->
</script>


</head>

<body>
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
        <form id="CreditWarningMessageform" name="CreditWarningMessageform" method="post" action="<?php if ($_GET['from'] == 'COCasePAID') { ?>../Admin/COCasePAID.php <?php } else if ($_GET['from'] == 'COCasePromoB') {?> ../Admin/COCasePromoB.php <?php  }?>">
           <input type="hidden" name="orderid" id="orderid" value="<?php echo $_GET['orderid']; ?>"/>
           <input type="hidden" name="counter" id="counter" value="<?php echo $_GET['counter']; ?>"/>
           <input type="hidden" name="clientid" id="clientid" value="<?php if (isset($_GET['clientid'])) echo $_GET['clientid']; ?>"/>
      <table width="473" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <?php
            if (isset($_GET['message'])){ ?>
                <tr>
                    <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo $_GET['message']; ?> 
                    </p></td>
                </tr>
             <?php }

           ?>
        <tr>
          <td width="277" height="44" align="left" valign="middle" class="style1">Cancel project and make a credit voucher : </td>
          <td width="196" align="left" valign="middle"><span class="style1">$</span>
            <input name="Credit Amount" type="text" class="style12" id="Credit Amount" value="<?php echo $_GET['amount'] ?>" size="25" maxlength="15" readonly=""  style="border:none"/></td>
        <tr>
          <td height="207" colspan="2" align="center" valign="top" class="style1"><p>Reason for cancellation:<br />
                <textarea name="reason" cols="60" rows="10" class="style1" id="reason"></textarea>
              <br />
          </p></td>
        <tr>
            <td height="69" colspan="2" align="center" valign="middle"><input name="CreditWarningMessage" type="image" src="../Images/GAMButtonSendOFF.jpg" value="Submit" width="140" height="66" id="Image1" onmouseover="MM_swapImage('Image1','','../Images/GAMButtonSendON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
        </table> 
    </form>
         <?php }
        ?>
</div>
</body>

    
</html>


