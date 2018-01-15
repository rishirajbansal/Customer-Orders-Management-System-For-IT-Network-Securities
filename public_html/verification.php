<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Registration.php';
include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Profile.php';
include_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'EmailNotification.php';
include_once 'functions.php';

$verificationmessage = NULL;
$profile = new Profile();

if (empty($_GET['email']) || empty($_GET['key'])){
   $action['result'] = 'error';
   $verificationmessage = 'Verification failed. Missing details. Please check your email.';			
}
else{
    $email = mysql_real_escape_string($_GET['email']);
    $key = mysql_real_escape_string($_GET['key']);
    
    $registration = new Registration();
    
    $isValid = $registration->verifyUserVerificationKey($key);
    if ($isValid){
        $result = $registration->updateVerification($key);
        if ($result){
            $action['result'] = 'success';
            $verificationmessage = 'Your verifications are confirmed. Thank-You!';

            
            $profile->fetchBasedOnEmail($email);
            //Send Email
            $mailInfo = array(
                        'fname' => $profile->getFName(),
                        'lname' => $profile->getLName(),
                        'email' => $email,
                        'password' => $profile->getPassword()
                        );
            $emailNotification = new EmailNotification();
            $mail = $emailNotification->sendWelcomeMessageEmail($mailInfo);

            if ($mail){
                $verificationmessage = 'Thanks for your confirmation. You will receive our welcome email shortly';
            }
            else{
                $verificationmessage = 'Your verifications are confirmed but failed to send the welcome message. Thank-You!';
            }
        }
        else{
            $action['result'] = 'error';
            $verificationmessage = 'Cannot be verified. Please contact Us.';
        }
    }
    else{
        $action['result'] = 'error';
        $verificationmessage = 'Verification failed. Invalid details.';	
    }
    
}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Log in Green Apple Mail is secure!">
<meta name="keywords" content="Email, validation, cleans, hygiene, invalid, valid, messages, mailboxes, cleanse, lists, undeliverable, validator, verification, verifier, emails, Email Validation Leaders, Green, Mail">
<title>Green Apple Mail  &copy; 2012 - Log In</title>

</head>

<body onload="javascript:submit()">

<div align="center">
  <table width="1000" border="0" cellpadding="0" cellspacing="0">
<form id="verificationform" name="verificationform" method="post" action="login.php">
    <input type="hidden" name="verificationmessage" id="verificationmessage" value="<?php echo $verificationmessage; ?>"/>

 </form>
  </table>
</div>
</body>
    
<script>    
    function submit(){
        document.verificationform.submit();
    }
</script>
    
</html>


