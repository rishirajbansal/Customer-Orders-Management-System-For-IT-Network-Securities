<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Profile.php';
include_once '../functions.php';

if (session_id() == "") 
    session_start();

$action = array();
$action['result'] = null;
$text = array();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    
    $profile = new Profile();
    $message = NULL;
    
    if (isset($_POST['personalinfo_x']) && isset($_POST['personalinfo_y'])){
        preventSQLInjectionAndValidate($profile);
        
        if($action['result'] != 'error'){
            $result = $profile->update();
            
            if ($result == 1){
                $action['result'] = 'success';
                array_push($text,'Your information has been updated successfully');
                $profile->fetchBasedOnEmail($_SESSION['email']);
            }
            else if ($result == 2){
                $action['result'] = 'error';
                array_push($text,'Email already exists. Please choose another.');
            }
            else if ($result == 3){
                $action['result'] = 'error';
                array_push($text,'Password is invalid. Please enter valid password.');
            }
            else{
                $action['result'] = 'error'; 
                array_push($text,'Sorry, failed to update profile. Please try after some time. Reason: ' . mysql_error());
            }
        }
        
        $action['text'] = $text;
        if($action['result'] != 'error'){
            $message = $text[0];
        }
    }
    else{
        $result = $profile->fetchBasedOnEmail($_SESSION['email']);
    }
    
    
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - Personal Information</title>
<link type="text/css" rel="stylesheet" href="../css/GAMmain.css">
<style type="text/css">
<!--
bodyy {
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
.style12 {
	font-size: 9px;
	color: #666666;
	font-family: Arial, Helvetica, sans-serif;
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

<body onload="MM_preloadImages('../Images/GAMButtonSaveON.jpg')">

<div align="center">
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
          <td width="385" height="60" valign="top"><img src="../Images/GAMPageTitlePersonalInfo.jpg" width="385" height="60" id="Image1" /></td>
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
      <td colspan="2" rowspan="3" valign="top">
       <table width="100%" border="0" cellpadding="0" cellspacing="0">
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
          <td width="958" height="70" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">Below is the information you entered, if you need to make any corrections or changes feel free to do so. Your information is private and will be used by our staff only to communicate with you. If you need help or have any questions, please contact us.<br />
            </p></td>
          </tr>
        <tr>
          <td height="359" valign="top" ><form id="USERpersonalform" name="USERpersonalform" method="post" action="">
            <div align="center">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                <!--DWLayoutTable-->
                <tr>
                  <td width="468" height="359" valign="top"><div align="center">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      
                      <!--DWLayoutTable-->
                      <tr>
                        <td width="216" height="31">&nbsp;</td>
                            <td width="252" align="left" valign="middle" ><strong class="style1">INFORMATION </strong></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">First name: </span></td>
                              <td align="left" valign="middle">
                                  <input name="USERfirstname" type="text" class="style1" id="USERfirstname" value="<?php echo $profile->getFName(); ?>" size="35" maxlength="60" />                            </td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Last name: </span></td>
                        <td align="left" valign="middle"><input name="USERlastname" type="text" class="style1" id="USERlastname" value="<?php echo $profile->getLName(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Company name: </span></td>
                        <td valign="middle"><input name="USERcompany" type="text" class="style1" id="USERcompany" value="<?php echo $profile->getCompanyName(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Address 1: </span></td>
                        <td align="left" valign="middle"><input name="USERaddress1" type="text" class="style1" id="USERaddress1" value="<?php echo $profile->getAddress1(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Address 2: </span></td>
                              <td align="left" valign="middle"><input name="USERaddress2" type="text" class="style1" id="USERaddress2"  value="<?php echo $profile->getAddress2(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">City:</span></td>
                        <td valign="middle"><input name="USERcity" type="text" class="style1" id="USERcity"  value="<?php echo $profile->getCity(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">State:</span></td>
                        <td valign="middle"><input name="USERstate" type="text" class="style1" id="USERstate"  value="<?php echo $profile->getState(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Zip Code:</span></td>
                        <td valign="middle"><input name="USERzipcode" type="text" class="style1" id="USERzipcode"  value="<?php echo $profile->getZipcode(); ?>" size="35" maxlength="30" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Country:</span></td>
                        <td valign="middle"><input name="USERcountry" type="text" class="style1" id="USERcountry"  value="<?php echo $profile->getCountry(); ?>" size="35" maxlength="60" /></td>
                            </tr>
                      <tr>
                        <td height="31" align="right" valign="middle"><span class="style1">Phone:</span></td>
                        <td valign="middle"><input name="USERphone" type="text" class="style1" id="USERphone"  value="<?php echo $profile->getPhone(); ?>" size="35" maxlength="35" /></td>
                            </tr>
                      </table>
						                                                                        
                    </div></td>
					                                                                        
			
                      <td width="490" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          
                          <!--DWLayoutTable-->
                          <tr>
                            <td width="240" height="31">&nbsp;</td>
                            <td colspan="2" align="left" valign="middle"><strong class="style1">SECURITY  </strong></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Client ID: </span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERid" type="text" class="style1" id="USERid" value="<?php echo $profile->getClientId(); ?>" size="35" maxlength="60" style="border:none;" readonly=""/></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Current Email:</span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERemail" type="text" class="style1" id="USERemail" value="<?php echo $profile->getEmail(); ?>" size="35" maxlength="60" style="border:none;" readonly=""/></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">New Email:</span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERnewemail" type="text" class="style1" id="USERnewemail" size="35" maxlength="60"/></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Re-enter Email:</span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERreemail" type="text" class="style1" id="USERreemail" size="35" maxlength="60" /></td>
                          </tr>
                          <tr>
                            <td height="31">&nbsp;</td>
                            <td colspan="2" align="left" valign="middle"><strong class="style1">CHANGE YOUR PASSWORD </strong></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Current Password:</span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERpassword" type="password" class="style1" id="USERpassword" value="" size="35" maxlength="60" /></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">New Password:</span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERnewpassword" type="password" class="style1" id="USERnewpassword"  value="" size="35" maxlength="60" /></td>
                          </tr>
                          <tr>
                            <td height="31" align="right" valign="middle"><span class="style1">Re-enter Password: </span></td>
                            <td colspan="2" align="left" valign="middle"><input name="USERrepassword" type="password" class="style1" id="USERrepassword"  value="" size="35" maxlength="60" /></td>
                          </tr>
                          <tr>
                            <td height="85" colspan="2" align="center" valign="middle"><div align="justify"><span class="style12">YOU ARE THE ONLY ONE ABLE TO MODIFY ANY OF THESE FIELDS EXCEPT FOR THE CLIENT ID NUMBER WHICH IS UNIQUE TO ALLOW US IDENTIFY YOU AND YOUR MATERIAL. BY PRESSING &ldquo;SAVE&rdquo; YOU ACKNOWLEDGE THAT YOU&rsquo;RE 100% RESPONSIBLE OF ANY ERRORS OCCURRED AS A RESULT OF ANY CHANGE(S) YOU MAKE. </span></div></td>
                            <td width="140" align="right" valign="top">
                              <label>
                              <input name="personalinfo" type="image" id="Image10" onmouseover="MM_swapImage('Image10','','../Images/GAMButtonSaveON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/GAMButtonSaveOFF.jpg" width="140" height="66" />
                            </label></td>
                          </tr>
                          <tr>
                            <td height="0"></td>
                            <td width="110"></td>
                            <td></td>
                          </tr>
                          
                          
                          
                          
                      </table></td>
                    </tr>
                  </table>
                </div>
            </form></td>
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
      <td height="192"></td>
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
</div>
</body>
</html>

<?php
}
else{
    header("location: ../login.php");
}

function preventSQLInjectionAndValidate(Profile $profile){
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
    $loginId = mysql_real_escape_string($_POST['USERid']);

    $newEmail = mysql_real_escape_string($_POST['USERnewemail']);
    $reNewEmail = mysql_real_escape_string($_POST['USERreemail']);
    $newPassword = mysql_real_escape_string($_POST['USERnewpassword']);
    $reNewPassword = mysql_real_escape_string($_POST['USERrepassword']);
    
    if(empty($fName)){ $action['result'] = 'error'; array_push($text,'First Name is required'); }
    if(empty($lName)){ $action['result'] = 'error'; array_push($text,'Last Name is required'); }
    if(empty($address1)){ $action['result'] = 'error'; array_push($text,'Address is required'); }
    if(empty($city)){ $action['result'] = 'error'; array_push($text,'City is required'); }
    if(empty($country)){ $action['result'] = 'error'; array_push($text,'Country is required'); }
    
    if(!empty($newEmail) && empty($reNewEmail)) { $action['result'] = 'error'; array_push($text,'New Mail & Re enter new Email should be same.'); }
    if(!empty($newEmail) && !empty($reNewEmail) && $newEmail != $reNewEmail) { $action['result'] = 'error'; array_push($text,'New Mail & Re enter new Email should be same.'); }
    
    if(!empty($password) && !empty($newPassword) && empty($reNewPassword)) { $action['result'] = 'error'; array_push($text,'New Password & Re enter new Password should be same.'); }
    if(!empty($password) && !empty($newPassword) && !empty($reNewPassword) && $newPassword != $reNewPassword) { $action['result'] = 'error'; array_push($text,'New Password & Re enter new Password should be same.'); }
    if(!empty($newPassword) && empty($password) ) { $action['result'] = 'error'; array_push($text,'Please enter current password.'); }
    
    
    $profile->setFName($fName);
    $profile->setLName($lName);
    $profile->setCompanyName($companyName);
    $profile->setAddress1($address1);
    $profile->setAddress2($address2);
    $profile->setCity($city);
    $profile->setState($state);
    $profile->setZipcode($zipcode);
    $profile->setCountry($country);
    $profile->setPhone($phone);
    $profile->setEmail($email);
    $profile->setPassword($password);
    $profile->setLoginId($loginId);
    $profile->setClientId($loginId);

    $profile->setNewEmail($newEmail);
    $profile->setReNewEmail($reNewEmail);
    $profile->setNewPassword($newPassword);
    $profile->setReNewPassword($reNewPassword);
    
}
    
?>