<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Profile.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
require_once 'PayflowNVPAPI.php';

if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {

if (session_id() == "") 
    session_start();

global $environment;
$environment = Config::$environment;

if (isset($_SESSION['curl_error']) && !empty($_SESSION['curl_error'])){ 
    unset($_SESSION['curl_error']);
}
if (isset($_SESSION['paypal_error']) && !empty($_SESSION['paypal_error'])){ 
    unset($_SESSION['paypal_error']);
}

//Check if we just returned inside the iframe.  If so, store payflow response and redirect parent window with javascript.
if (isset($_POST['RESULT']) || isset($_GET['RESULT']) ) {
  $_SESSION['payflowresponse'] = array_merge($_GET, $_POST);
  echo '<script type="text/javascript">window.top.location.href = "' . script_url() .  '";</script>';
  exit(0);
}

//Check whether we stored a server response. 
if (!empty($_SESSION['payflowresponse'])) {
    
  $response = $_SESSION['payflowresponse'];
  //unset($_SESSION['payflowresponse']);

  $success = ($response['RESULT'] == 0);

  if ($success) {
      header('location: ../securezone/checkoutSuccessful.php');
       exit(0);
  }
  else {
      $_SESSION['paypal_error'] = $response;
            
      header('location: ../securezone/payments.php');
       exit(0);
  }
}

$profile = new Profile();
$profile->fetchBasedOnEmail($_SESSION['email']);
$order = $_SESSION['userOrder'];

//Build the Secure Token request
$request = array(
  "PARTNER" => Config::$payflow_partner,
  "VENDOR" => Config::$payflow_vendor,
  "USER" => Config::$payflow_user,
  "PWD" => Config::$payflow_password,
  "TRXTYPE" => "S",
  "AMT" => $order->getPayflowAmount(),
  "CURRENCY" => Config::$payflow_currency,
  "CREATESECURETOKEN" => "Y",
  "SECURETOKENID" => guid(),
  "RETURNURL" => script_url(),
  "CANCELURL" => script_url(),
  "ERRORURL" => script_url(),
  "COMMENT1"    => 'Order id: '.$order->getOrderNo().' Project Name: '.$order->getProjectName() ,
  "EMAIL"   => $profile->getEmail(),

  "BILLTOFIRSTNAME" => $profile->getFName(),
  "BILLTOLASTNAME" => $profile->getLName(),
  "BILLTOSTREET" => $profile->getAddress1() . ' ' . $profile->getAddress2(),
  "BILLTOCITY" => $profile->getCity(),
  "BILLTOSTATE" => $profile->getState(),
  "BILLTOZIP" => $profile->getZipcode(),
  "BILLTOCOUNTRY" => $profile->getCountry(),
  "BILLTOPHONENUM" => $profile->getPhone(),

  "SHIPTOFIRSTNAME" => $profile->getFName(),
  "SHIPTOLASTNAME" => $profile->getLName(),
  "SHIPTOSTREET" => $profile->getAddress1() . ' ' . $profile->getAddress2(),
  "SHIPTOCITY" => $profile->getCity(),
  "SHIPTOSTATE" => $profile->getState(),
  "SHIPTOZIP" => $profile->getZipcode(),
  "SHIPTOCOUNTRY" => $profile->getCountry(),
);

//Run request and get the secure token response
$response = run_payflow_call($request);

if ($response){
    if (!empty($response['curl'])){
        $_SESSION['curl_error'] = $response['curl'];
        
         header('location: ../securezone/payments.php');
         exit(0);
    }
    else{
        $paypalresponse = $response['paypal'];
        if ($paypalresponse['RESULT'] != 0) {
            $_SESSION['paypal_error'] = $paypalresponse;
            
             header('location: ../securezone/payments.php');
              exit(0);
        }
        else{
            $securetoken = $paypalresponse['SECURETOKEN'];
            $securetokenid = $paypalresponse['SECURETOKENID'];
        }
    }
}
?>
<?php
$payflow_mode = Config::$payflow_mode;
echo "  <iframe src='https://payflowlink.paypal.com?SECURETOKEN=$securetoken&SECURETOKENID=$securetokenid&MODE=$payflow_mode' width='490' height='565' border='0' frameborder='0' scrolling='no' allowtransparency='true'>\n</iframe>";
    ?>

<!--
<form id="PAYPALform" name="PAYPALform" method="post" action="https://payflowlink.paypal.com">
  <input type="hidden" name="SECURETOKEN" value="<?php echo $securetoken; ?>">
  <input type="hidden" name="SECURETOKENID" value="<?php echo $securetokenid; ?>">
  <input type="hidden" name="MODE" value="<?php echo Config::$payflow_mode; ?>">
  
</form> -->

<script>
    document.PAYPALform.submit();
</script>    

<?php


?>

<?php
}
else{
    header("location: ../login.php");
}
?>