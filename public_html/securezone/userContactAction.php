
<link type="text/css" rel="stylesheet" href="../css/GAMmain.css">
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
.WHITEbig {color: #FFFFFF; font-weight: bold; font-size: 24px; 
}
-->
</style>

<?php

include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'EmailNotification.php';


//Send Email notification
$mailInfo = array(
            'fname' => $_POST["USERfirstname"],
            'lname' => $_POST["USERlastname"],
            'email'   => $_POST["USERemail"],
            'message' => $_POST["comments"],
            'clientid' => $_POST["USERid"],
            'ip'    => $_SERVER['REMOTE_ADDR']
            );
$emailNotification = new EmailNotification();

//echo "Redirecting ...";
echo "<meta http-equiv=\"refresh\" content=\"2;URL=userContactThanks.php\">"; 

$result = $emailNotification->sendContactUsEmail($mailInfo);
if (!$result){
    echo "Could not send email.";
}
?>
<div align="center">
  <table width="383" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
      <td width="383" height="88" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    </tr>
    
    <tr>
      <td height="71" valign="top"><div align="center"><span class="WHITEbig">PROCESSING . . . </span></div></td>
    </tr>
    
    <tr>
      <td height="62" valign="top" bgcolor="#FFFFFF"><div align="center"><img src="../Images/COUNTER-UPDATING.gif" width="40" height="40" /></div></td>
    </tr>
    <tr>
      <td height="43" valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
    </tr>
    
    
    
    <tr>
      <td height="43" valign="top" bgcolor="#FFFFFF"><p align="center" class="style1">IF YOU HAVE WAITING MORE THAN 2 MINUTES,<br />
      PLEASE CANCEL THIS PROCESS </p></td>
    </tr>
  </table>
</div>