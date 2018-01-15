<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'AdminUser.php';

include_once '../functions.php';

if (session_id() == "") 
    session_start();

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $adminUser = new AdminUser();
    
    $admins = NULL;
    
    if (isset($_POST['adminsetup_x']) && isset($_POST['adminsetup_y'])){       
        
        $newAdmins = array();
        preventSQLInjectionAdminSetup();
        
        if($action['result'] != 'error'){
            $adminUser->setAdmins($newAdmins);
            $result = $adminUser->saveAdminUsers();
            
            if ($result){
                $action['result'] = 'success';
                array_push($text,'Admins are setup successfully.');
            }
            else if ($result){
                $action['result'] = 'error';
                array_push($text,'Admin setup failed. Reason :'. mysql_error());
            }
        }
        $adminUser->fetchAdmins();
        $admins = $adminUser->getAdmins();
        $adminUser->fetchFTPDetails();
    }
    else if (isset($_POST['ftpsetup_x']) && isset($_POST['ftpsetup_y'])){       
        
        $newAdmins = array();
        preventSQLInjectionFTP($adminUser);
        
        if($action['result'] != 'error'){
            $result = $adminUser->saveFTPDetails();
            
            if ($result){
                $action['result'] = 'success';
                array_push($text,'FTP Details are setup successfully.');
            }
            else if ($result){
                $action['result'] = 'error';
                array_push($text,'FTP Details setup failed. Reason :'. mysql_error());
            }
        }
        $adminUser->fetchAdmins();
        $admins = $adminUser->getAdmins();
    }
    else{
        $adminUser->fetchAdmins();
        $adminUser->fetchFTPDetails();
        $admins = $adminUser->getAdmins();
    }
    
    $action['text'] = $text; 
    if($action['result'] && $action['result'] != 'error'){
        $message = $text[0];
    }
       
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - SURVEY</title>
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

<body onload="MM_preloadImages('../Images/GAMButtonSaveON.jpg')">
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
src="http://c.statcounter.com/8682132/0/f2b50c78/1/"
alt="web analytics"
class="statcounter" id="Image1"></a></div>
</noscript>
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
          <td width="385" height="60" valign="top"><img src="../Images/AdminImages/GAMPageAdminSetup.jpg" width="385" height="60" /></td>
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
      <td colspan="2" rowspan="3" valign="top">
          <form id="ADMINsetupform" name="ADMINsetupform" method="post" action="">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <?php
         if ($message){ ?>
             <tr>
                 <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo $message; ?> 
                 </p></td>
             </tr>
          <?php }
          if(!empty($action['result']) && $action['result'] == 'error'){  ?>
            <tr>
                 <td width="958" height="20" valign="top" bgcolor="#FF0505" align="left"><p align="justify" class="style1"><?php echo show_errors($action); ?> 
                 </p></td>
             </tr>
        <?php  }
        ?>
        <tr>
          <td width="958" height="22" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">ADMINISTRATORS:<br />
            </p></td>
          </tr>
        <tr>
          <td height="206" valign="top" >
              
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="27" height="35" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">1 - </td>
                  <td width="75" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Email : </td>
                  <td width="316" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminEmail1" type="text" class="style1"  id="AdminEmail1" value="<?php if(!empty($admins[1])){ echo $admins[1]['email']; } ?>" size="40" maxlength="40" /></td>
                  <td width="98" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Password : </td>
                  <td width="442" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminPassw1" type="text" class="style1"  id="AdminPassw1" value="<?php if(!empty($admins[1])){ echo $admins[1]['password']; } ?>" size="40" maxlength="40" /></td>
                </tr>
            
            <tr>
              <td height="35" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">2 - </td>
                  <td width="75" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Email : </td>
                  <td width="316" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminEmail2" type="text" class="style1"  id="AdminEmail2" value="<?php if(!empty($admins[2])){ echo $admins[2]['email']; } ?>" size="40" maxlength="40" /></td>
                  <td width="98" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Password : </td>
                  <td width="442" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminPassw2" type="text" class="style1"  id="AdminPassw2" value="<?php if(!empty($admins[2])){ echo $admins[2]['password']; } ?>" size="40" maxlength="40" /></td>
                </tr>
            <tr>
              <td height="35" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">3 - </td>
                  <td width="75" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Email : </td>
                  <td width="316" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminEmail3" type="text" class="style1"  id="AdminEmail3" value="<?php if(!empty($admins[3])){ echo $admins[3]['email']; } ?>" size="40" maxlength="40" /></td>
                  <td width="98" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Password : </td>
                  <td width="442" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminPassw3" type="text" class="style1"  id="AdminPassw3" value="<?php if(!empty($admins[3])){ echo $admins[3]['password']; } ?>" size="40" maxlength="40" /></td>
                </tr>
            <tr>
                  <td height="35" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">4 - </td>
                  <td width="75" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Email : </td>
                  <td width="316" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminEmail4" type="text" class="style1"  id="AdminEmail4" value="<?php if(!empty($admins[4])){ echo $admins[4]['email']; } ?>" size="40" maxlength="40" /></td>
                  <td width="98" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Password : </td>
                  <td width="442" align="left" valign="middle" bgcolor="#FFFFFF"><input name="AdminPassw4" type="text" class="style1"  id="AdminPassw4" value="<?php if(!empty($admins[4])){ echo $admins[4]['password']; } ?>" size="40" maxlength="40" /></td>
	            </tr>
            
            <tr><td height="66" colspan="5" align="center" valign="top" bgcolor="#FFFFFF">
              <label>
                <input name="adminsetup" type="image" id="Image19" onmouseover="MM_swapImage('Image19','','../Images/GAMButtonSaveON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/GAMButtonSaveOFF.jpg" width="140" height="66" />
                </label></td>
            </table>
            
              </td>
          </tr>
        <tr>
          <td height="189" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="958" height="35" align="left" valign="bottom" bgcolor="#FFFFFF"><span class="style1">DATA BANK LOCATION:</span></td>
                </tr>
            <tr>
              <td height="146" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="171" height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">FTP address : </td>
                  <td colspan="3" align="left" valign="middle" bgcolor="#FFFFFF"><input name="FTPDataBank" type="text"  class="style1"  id="FTPDataBank" value="<?php echo $adminUser->getFtpAddress(); ?>" size="105" maxlength="100" /></td>
                      </tr>
                <tr>
                  <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">User :</td>
                  <td width="286" align="left" valign="middle" bgcolor="#FFFFFF"><input name="UserDataBank" type="text" class="style1"  id="UserDataBank" value="<?php echo $adminUser->getFtpUser(); ?>" size="40" maxlength="40" /></td>
                        <td width="79" align="right" valign="middle" bgcolor="#FFFFFF" class="style1">Password : </td>
                        <td width="426" align="left" valign="middle" bgcolor="#FFFFFF"><input name="PasswdDataBank" type="text" class="style1"  id="PasswdDataBank" value="<?php echo $adminUser->getFtpPassword(); ?>" size="40" maxlength="40" /></td>
                      </tr>
                <tr><td height="66" colspan="4" align="center" valign="top" bgcolor="#FFFFFF">
                  <label>
                    <input name="ftpsetup" type="image" id="Image22" onmouseover="MM_swapImage('Image22','','../Images/GAMButtonSaveON.jpg',1)" onmouseout="MM_swapImgRestore()" value="Submit" src="../Images/GAMButtonSaveOFF.jpg" width="140" height="66" />
                    </label></td>
                </table></td>
                </tr>
            <tr>
              <td height="8"></td>
                </tr>
            
            
            </table></td>
          </tr>
        <tr>
          <td height="6" ></td>
          </tr>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </table>
         </form>
      </td>
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
      <td height="186"></td>
      <td></td>
    </tr>
    <tr>
      <td height="6"></td>
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

function preventSQLInjectionAdminSetup(){
    global $newAdmins;
    
    $email = mysql_real_escape_string($_POST['AdminEmail1']);
    $password = mysql_real_escape_string($_POST['AdminPassw1']);
    
    if (!empty($email) && !empty($password)){
        $record = array(
                'email' => $email,
                'password' => $password
               );
        $newAdmins[1] = $record;
    }
    
    $email = mysql_real_escape_string($_POST['AdminEmail2']);
    $password = mysql_real_escape_string($_POST['AdminPassw2']);
    
    if (!empty($email) && !empty($password)){
        $record = array(
                'email' => $email,
                'password' => $password
               );
        $newAdmins[2] = $record;
    }
    
    $email = mysql_real_escape_string($_POST['AdminEmail3']);
    $password = mysql_real_escape_string($_POST['AdminPassw3']);
    
    if (!empty($email) && !empty($password)){
        $record = array(
                'email' => $email,
                'password' => $password
               );
        $newAdmins[3] = $record;
    }
    
    $email = mysql_real_escape_string($_POST['AdminEmail4']);
    $password = mysql_real_escape_string($_POST['AdminPassw4']);
    
    if (!empty($email) && !empty($password)){
        $record = array(
                'email' => $email,
                'password' => $password
               );
        $newAdmins[4] = $record;
    }
     
 }
 
 function preventSQLInjectionFTP(AdminUser $adminUser){
     global $action, $text;
     
     $ftpAddress = mysql_real_escape_string($_POST['FTPDataBank']);
     $ftpUser = mysql_real_escape_string($_POST['UserDataBank']);
     $ftpPassword = mysql_real_escape_string($_POST['PasswdDataBank']);
     
     if(empty($ftpAddress)){ $action['result'] = 'error'; array_push($text,'FTP Address is required'); }
     if(empty($ftpUser)){ $action['result'] = 'error'; array_push($text,'FTP User is required'); }
     if(empty($ftpPassword)){ $action['result'] = 'error'; array_push($text,'FTP Password is required'); }
     
     $adminUser->setFtpAddress($ftpAddress);
     $adminUser->setFtpUser($ftpUser);
     $adminUser->setFtpPassword($ftpPassword);
 }
 
 
 
?>