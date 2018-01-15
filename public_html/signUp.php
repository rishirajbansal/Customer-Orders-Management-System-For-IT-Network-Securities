<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Registration.php';
include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'EmailNotification.php';
include_once 'functions.php';

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (isset($_POST['signup_x']) && isset($_POST['signup_y'])){
    
    $registration = new Registration();
    
    preventSQLInjectionAndValidate($registration);
    
    if($action['result'] != 'error'){
        $isExist = $registration->isUserExists();
        
        if ($isExist){
            $action['result'] = 'error'; 
            array_push($text,'Sorry, the email you entered already exists.');
        }
        else{
            $flag = $registration->save();
            if ($flag){
                //Send Email notification
                $mailInfo = array(
                            'fname' => $registration->getFName(),
                            'lname' => $registration->getLName(),
                            'key'   => $registration->getClientId(),
                            'email' => $registration->getEmail()
                            );
                $emailNotification = new EmailNotification();
                $result = $emailNotification->sendRegistrationEmail($mailInfo);
                if ($result){
                    $action['result'] = 'success';
                    array_push($text,'Thanks for signing up. Please check your email for verification!');
                }
                else{
                    $action['result'] = 'error';
                    array_push($text,'Could not send verification email. Contact Us.');
                }
            }
            else{
                $action['result'] = 'error'; 
                array_push($text,'Sorry, failed to register. Please try after some time. Reason: ' . mysql_error());
            }
        }
    }
    
    $action['text'] = $text;
    if($action['result'] != 'error'){
        $message = $text[0];
    }
    
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Sign up with Green Apple Mail today and start validating your lists of email addresses! Signing-up takes less than 30 seconds.">
<meta name="keywords" content="Email, validation, cleans, hygiene, invalid, valid, messages, mailboxes, cleanse, lists, undeliverable, validator, verification, verifier, emails, Email Validation Leaders, Green, Mail">
<title>Green Apple Mail  &copy; 2012 - Sign Up</title>
<style type="text/css">
<!--
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
.style1111 {
	color: #ffffff;
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
.style3 {color: #666666; font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin-right: 0px; margin-left: 0px; line-height: 22px; }
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
          <td width="1000" height="104" valign="top"><iframe src="header.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="Images/GAMPageSignUp.jpg" width="385" height="60" /></td>
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
          <td width="958" height="31" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">Signing-up takes less than 30 seconds.  <br />
            <br />
            </p></td>
          </tr>
        <tr>
          <td height="286" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <form id="signupform" name="signupform" method="post" action="">
              <!--DWLayoutTable-->
              <tr>
                <td height="31" colspan="2" align="right" valign="top"><span class="style1">First name: </span></td>
                     <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                     <input name="USERfirstname" type="text" class="style1" id="USERfirstname" size="40" maxlength="60" />                            </td>
                  </tr>
              <tr>
                <td height="31" colspan="2" align="right" valign="top" ><span class="style1">Last name: </span></td>
                    <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                    <input name="USERlastname" type="text" class="style1" id="USERlastname" size="40" maxlength="60" /></td>
                  </tr>
              <tr>
                <td height="31" colspan="2" align="right" valign="top"><span class="style1">Company name : </span></td>
                    <td colspan="2" align="left" valign="middle">
                    <input name="USERcompany" type="text" class="style1" id="USERcompany" size="40" maxlength="60" />
                    <span class="style3">(Optional)</span></td>
                  </tr>
				  
				  <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">Address 1: </span></td>
                              <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                               <input name="USERaddress1" type="text" class="style1" id="USERaddress1" size="40" maxlength="60" /></td>
                  </tr>
                      <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">Address 2: </span></td>
                              <td colspan="2" align="left" valign="middle">
                              <input name="USERaddress2" type="text" class="style1" id="USERaddress2" size="40" maxlength="60" /></td>
                  </tr>
                      <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">City:</span></td>
                              <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                              <input name="USERcity" type="text" class="style1" id="USERcity"  size="40" maxlength="60" /></td>
                  </tr>
                      <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">State:</span></td>
                              <td colspan="2" align="left" valign="middle">
                              <input name="USERstate" type="text" class="style1" id="USERstate" size="40" maxlength="60" /></td>
                  </tr>
                      <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">Zip Code:</span></td>
                              <td colspan="2" align="left" valign="middle">
                              <input name="USERzipcode" type="text" class="style1" id="USERzipcode" size="40" maxlength="30" /></td>
                  </tr>
                      <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">Country:</span></td>
                              <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                              <input name="USERcountry" type="text" class="style1" id="USERcountry"  size="40" maxlength="60" /></td>
                  </tr>
                      <tr>
                        <td height="31" colspan="2" align="right" valign="middle"><span class="style1">Phone:</span></td>
                              <td colspan="2" align="left" valign="middle" >
                              <input name="USERphone" type="text" class="style1" id="USERphone" size="40" maxlength="60" /></td>
                  </tr>
				  
              <tr>
                <td height="31" colspan="2" align="right" valign="top"><span class="style1">Email: </span></td>
                    <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                     <input name="USERemail" type="text" class="style1" id="USERemail"  size="40" maxlength="60" /></td>
                  </tr>
              <tr>
                <td height="31" colspan="2" align="right" valign="top"><span class="style1">Password: </span></td>
                    <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                    <input name="USERpassword" type="password" class="style1" id="USERpassword" size="40" maxlength="60" /></td>
                  </tr>
              <tr>
                <td height="31" colspan="2" align="right" valign="top"><span class="style1">Re-enter password :</span></td>
                    <td colspan="2" align="left" valign="middle" background="Images/ArrowsForm.jpg">
                    <input name="USERrepassword" type="password" class="style1" id="USERrepassword"  size="40" maxlength="60" /></td>
                  </tr>
              <tr>
                <td width="116" height="100" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td colspan="2" align="center" valign="bottom">
                    <label>
                      <input name="signup" type="image" SRC="Images/GAMButtonSignNowOFF.jpg" onclick="MM_validateForm('USERfirstname','','R','USERlastname','','R','USERaddress1','','R','USERcity','','R','USERcountry','','R','USERemail','','RisEmail','USERpassword','','R','USERrepassword','','R');return document.MM_returnValue" value="Submit" width="140" height="74" id="Image1" onmouseover="MM_swapImage('Image1','','Images/GAMButtonSignNowON.jpg',1)" onmouseout="MM_swapImgRestore()"/>
                    </label></td>
                  <td width="103" valign="top"><img src="Images/SECURITYlogo100.jpg" width="100" height="100" /></td>
                  </tr>
              
              <tr>
                <td height="0"></td>
                <td width="262"></td>
                <td width="477"></td>
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


<?php

function preventSQLInjectionAndValidate(Registration $registration){
    global $action, $text;
    
    $fName = mysql_real_escape_string($_POST['USERfirstname']);
    $lName = mysql_real_escape_string($_POST['USERlastname']);
    $companyName = mysql_real_escape_string($_POST['USERcompany']);
    $address1 = mysql_real_escape_string($_POST['USERaddress1']);
    $address2 = mysql_real_escape_string($_POST['USERaddress2']);
    $city = mysql_real_escape_string($_POST['USERcity']);
    $state = mysql_real_escape_string($_POST['USERstate']);
    $zipcode = mysql_real_escape_string($_POST['USERzipcode']);
    $country = mysql_real_escape_string($_POST['USERcountry']);
    $phone = mysql_real_escape_string($_POST['USERphone']);
    $email = mysql_real_escape_string($_POST['USERemail']);
    $password = mysql_real_escape_string($_POST['USERpassword']);
    $reNewPassword = mysql_real_escape_string($_POST['USERrepassword']);
    
    if(empty($fName)){ $action['result'] = 'error'; array_push($text,'First Name is required'); }
    if(empty($lName)){ $action['result'] = 'error'; array_push($text,'Last Name is required'); }
    if(empty($address1)){ $action['result'] = 'error'; array_push($text,'Address is required'); }
    if(empty($city)){ $action['result'] = 'error'; array_push($text,'City is required'); }
    if(empty($country)){ $action['result'] = 'error'; array_push($text,'Country is required'); }
    if(empty($email)){ $action['result'] = 'error'; array_push($text,'Email is required'); }
    if(empty($password)){ $action['result'] = 'error'; array_push($text,'Password is required'); }
    if(empty($reNewPassword)){ $action['result'] = 'error'; array_push($text,'Re-enter Password is required'); }
    
    if(!empty($password) && !empty($reNewPassword) && ($password != $reNewPassword)) { $action['result'] = 'error'; array_push($text,'New Password & Re enter new Password should be same.'); }
    
    if(!empty($email) && !verifyEmailDomain($email)){ $action['result'] = 'error'; array_push($text,'Please enter valid email. Domain verification of your email failed.'); }
    
    $registration->setFName($fName);
    $registration->setLName($lName);
    $registration->setCompanyName($companyName);
    $registration->setAddress1($address1);
    $registration->setAddress2($address2);
    $registration->setCity($city);
    $registration->setState($state);
    $registration->setZipcode($zipcode);
    $registration->setCountry($country);
    $registration->setPhone($phone);
    $registration->setEmail($email);
    $registration->setPassword($password);
    
}

function verifyEmailDomain($email){
    
    $split = explode('@', $email);
    $domain = array_pop($split);
    if (!checkdnsrr($domain,'MX')) {
        return false;
    }
    else {
        return true;
    }
    
}
    
?>