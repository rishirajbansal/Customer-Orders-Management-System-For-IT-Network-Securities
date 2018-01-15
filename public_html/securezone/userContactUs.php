<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Profile.php';

if (session_id() == "") 
    session_start();


if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    
    $profile = new Profile();
    
    $profile->fetchBasedOnEmail($_SESSION['email']);
    

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - Contact Us</title>
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

<body onload="MM_preloadImages('../Images/GAMButtonSendON.jpg')">
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
   <form id="USERcontactform" name="USERcontactform" method="post" action="userContactAction.php">
  <table width="1000" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td height="104" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1000" height="104" valign="top"><iframe src="../securezone/header.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="../Images/GAMPageContactUs.jpg" width="385" height="60" /></td>
          </tr>
      </table></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="615" height="60" valign="top"><iframe src="../securezone/submenu.php" width="615" height="60" frameborder="0" scrolling="no" ></iframe></td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td height="36" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="241" height="36" valign="top"><img src="../Images/GAMUserLowBarPlaceOrderNOACTIVE.jpg" width="241" height="36" /></td>
            <td width="759" valign="top"><img src="../Images/GAMUserLowBarREST.jpg" width="759" height="36" /></td>
          </tr>
        
      </table></td>
    </tr>
    <tr>
      <td width="21" height="10"></td>
      <td colspan="2" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="958" height="70" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">If you have any questions or need further information about our services, please feel free to contact us; if this is a matter related to technical support, don't hesitate to do so as well, it&rsquo;s free of charge. <br />
            <br />
            </p></td>
          </tr>
        <tr>
          <td height="371" valign="top" ><div align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
              <!--DWLayoutTable-->
              <tr>
                <td width="207" height="371">&nbsp;</td>
                    <td width="468" valign="top"><div align="center">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          
                            <!--DWLayoutTable-->
                            <tr>
                              <td width="158" height="31" align="right" valign="middle"><span class="style1">First name: </span></td>
                              <td width="310" align="left" valign="middle">
                                  <input name="USERfirstname" type="text" class="style1" id="USERfirstname" value="<?php echo $profile->getFName(); ?>" size="40" maxlength="60"  style="border:none;"/>                            </td>
                            </tr>
                            <tr>
                              <td height="31" align="right" valign="middle"><span class="style1">Last name: </span></td>
                              <td align="left" valign="middle"><input name="USERlastname" type="text" class="style1" id="USERlastname" value="<?php echo $profile->getLName(); ?>" size="40" maxlength="60" style="border:none;"/></td>
                            </tr>
                            <tr>
                              <td height="31" align="right" valign="middle"><span class="style1">Email: </span></td>
                              <td align="left" valign="middle"><input name="USERemail" type="text" class="style1" id="USERemail" value="<?php echo $profile->getEmail(); ?>" size="40" maxlength="60" style="border:none;"/></td>
                            </tr>
                            <tr>
                              <td height="31" align="right" valign="middle"><span class="style1">Client ID:</span></td>
                              <td valign="middle"><input name="USERid" type="text" class="style1" id="USERid" value="<?php echo $profile->getClientId(); ?>" size="40" maxlength="60" style="border:none;"/></td>
                            </tr>
                            <tr>
                              <td height="31" align="right" valign="middle"><span class="style1">Comments: </span></td>
                              <td rowspan="3" align="left" valign="middle"><textarea name="comments" cols="42" rows="8" class="style1" style="overflow:auto;resize:none;" id="comments"></textarea></td>
                            </tr>
                            
                            <tr>
                              <td height="125">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="66" align="center" valign="top">
                                <label>
                                <input name="Submit" type="image" src="../Images/GAMButtonSendOFF.jpg" value="Submit" width="140" height="66" id="Image1" onmouseover="MM_swapImage('Image1','','../Images/GAMButtonSendON.jpg',1)" onmouseout="MM_swapImgRestore()" />
                                  </label></td>
                            </tr>
                            
                            <tr>
                              <td height="25">&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                          
                        </table>
						                                                                        
                    </div></td>
					                                                                        
			
                    <td width="283">&nbsp;</td>
                  </tr>
                  </table>
				                                                                        
				
            </div></td>
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
      <td height="204"></td>
      <td></td>
    </tr>
    <tr>
      <td height="160"></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="958" height="160" valign="top"><iframe src="../securezone/footer.php" width="958" height="160" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
      <td></td>
    </tr>
    <tr>
      <td height="1"></td>
      <td width="364"></td>
      <td width="594"></td>
      <td></td>
    </tr>
  </table>
       </form>
</div>
</body>
</html>


<?php
}
else{
    header("location: ../login.php");
}
?>