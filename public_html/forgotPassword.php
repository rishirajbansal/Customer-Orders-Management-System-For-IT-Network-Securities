<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Login.php';
include_once 'functions.php';

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (isset($_POST['forgotPassword_x']) && isset($_POST['forgotPassword_y'])){
    
    $login = new Login();
    
    $email = mysql_real_escape_string($_POST['email']);
    if(empty($email)){ $action['result'] = 'error'; array_push($text,'Email is required'); }
    $login->setEmail($email);
    
    if($action['result'] != 'error'){
    
        $flag = $login->forgotPassword();

        if ($flag == 0){
            $message = 'Not found';
        }
        else if ($flag == 1){
            $action['result'] = 'error'; 
            array_push($text,'Sorry, failed to update new password. Please try after some time. Reason: ' . mysql_error());
        }
        else if ($flag == 2){
            $action['result'] = 'error'; 
            array_push($text,'Sorry, failed to send mail notificaiton for your forgot password. Please try after some time. Reason: ' . mysql_error());
        }
        else{
            //Success
            header('location: forgotPasswordSuccess.php');
        }
    }

    $action['text'] = $text;
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Password Recovery for Green Apple Mail.">
<meta name="keywords" content="Email, validation, cleans, hygiene, invalid, valid, messages, mailboxes, cleanse, lists, undeliverable, validator, verification, verifier, emails, Email Validation Leaders, Green, Mail">
<title>Green Apple Mail  &copy; 2012 - Password Recovery</title>
<link type="text/css" rel="stylesheet" href="css/GAMmain.css">
<!--<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	background-image: url(Images/BackgdGAMsite.jpg);
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
.style4 {
	color: #666666;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
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

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>

<body onload="MM_preloadImages('Images/GAMButtonSignNowON.jpg')">
<div align="center">
  <table width="1000" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td height="104" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1000" height="104" valign="top"><iframe src="header.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="Images/GAMPageTitleLogIn.jpg" width="385" height="60" /></td>
          </tr>
      </table></td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="615" height="60" valign="top"><iframe src="submenu.php" width="615" height="60" frameborder="0" scrolling="no" ></iframe></td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td width="21" height="46"></td>
      <td colspan="2" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <?php
          if(!empty($action['result']) && $action['result'] == 'error'){  ?>
            <tr>
                 <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo show_errors($action); ?> 
                 </p></td>
             </tr>
        <?php  }
        ?>
        <tr>
          <td width="958" height="31" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">PASSWORD RECOVERY: Enter your Email and pulse SEND. Then, go to your Email address to get your password.<br />
            <br />
            </p></td>
          </tr>
        <tr>
          <td height="286" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <form id="recoveryform" name="recoveryform" method="post" action="">
              <!--DWLayoutTable-->
              <tr>
                <td height="31" colspan="3" align="right" valign="top"><span class="style1">Email: </span></td>
                     <td colspan="3" align="left" valign="middle">
                     <input name="email" type="text" class="style1" id="email" size="40" maxlength="60" />                            </td>
                  </tr>
              <tr>
                <td width="99" rowspan="3" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td height="64" colspan="4" align="center" valign="top">
                      <label>
                      <input name="forgotPassword" type="image" src="Images/GAMButtonSendOFF.jpg" onclick="MM_validateForm('userid','','R');return document.MM_returnValue" value="Submit" width="140" height="64" id="Image1" onmouseover="MM_swapImage('Image1','','Images/GAMButtonSendON.jpg',1)" onmouseout="MM_swapImgRestore()"/>
                            </label></td>
                  <td width="101" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  </tr>
       
                <tr>
                  <td width="142" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td height="91" colspan="2" align="center" valign="top"><span class="style1">
                          <?php
                        if ($message){ ?> 
                          The email you entered does not match our records.<br />
                            Please try again or <a href="signUp.php">Sign Up!</a></span>
                  <?php   } ?>
                  </td>
                  <td width="179" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  </tr>
            
              
              

              
              
              <tr>
                <td width="104" height="100">&nbsp;</td>
                <td width="333">&nbsp;</td>
                  <td align="right" valign="top"><img src="Images/SECURITYlogo100.jpg" width="100" height="100" /></td>
                  </tr>
              <tr>
                <td height="0"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
                </form>
            </table></td>
          </tr>
        <tr>
          <td height="418" valign="top" ><iframe src="videoSection.php" width="958" height="418" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </table></td>
      <td width="21"></td>
    </tr>
    
    <tr>
      <td height="227" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="21" height="135" valign="top"><img src="Images/GAMLEFTspace.jpg" width="21" height="135" /></td>
          </tr>
        <tr>
          <td height="92">&nbsp;</td>
          </tr>
      </table></td>
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="21" height="143" valign="top"><img src="Images/GAMRIGHT-space.jpg" width="21" height="143" /></td>
          </tr>
        <tr>
          <td height="84">&nbsp;</td>
          </tr>
        
        
      </table></td>
    </tr>
    <tr>
      <td height="462">&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td height="160">&nbsp;</td>
      <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="958" height="160" valign="top"><iframe src="footer.php" width="958" height="160" frameborder="0" scrolling="no" ></iframe></td>
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
</div>
</body>
</html>
