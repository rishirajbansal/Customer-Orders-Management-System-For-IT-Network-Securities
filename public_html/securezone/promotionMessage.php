<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Order.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'UserOrder.php';
include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Promotion.php';
include_once '../functions.php';

if (session_id() == "") 
    session_start();

$action = array();
$action['result'] = null;
$text = array();
$message = NULL;

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {
    
    $order = !empty($_SESSION['userOrder']) ? $_SESSION['userOrder'] : NULL;
    $promo_detail = !empty($_SESSION['userOrder_promodetail']) ? $_SESSION['userOrder_promodetail'] : NULL;
    
    $promo_detail = $_SESSION['userOrder_promodetail'];
    $promoADetail = $promo_detail['promoADetail'];
    $promoBDetail = $promo_detail['promoBDetail'];
    
    if (!empty($promoADetail) && $_GET['pcode'] == $promoADetail['promocode'] ){
        $promo_detail = $promo_detail['promoADetail'];
    } 
    else {
        $promo_detail = $promo_detail['promoBDetail'];
    }

    $finePrint = $promo_detail['fineprint'];
    
    /*if (isset($_POST['promoSubmit'])){
        $promoCode = mysql_real_escape_string($_POST['pcode']);
        
        if (!empty($promo_detail)){
            if ($promoCode != $promo_detail['promocode']){
                $action['result'] = 'error';
                array_push($text,'Sorry, promo code is not vaild.');
            }
            else{
                $order->setPromoCode($promoCode);
                $result = $order->applyPromotion();
                if ($result){
                    $_SESSION['userOrder'] = $order;
                    $action['result'] = 'success';
                    $message = 'Promotion code is applied successfully.';
                }
                else{
                    $action['result'] = 'error';
                    array_push($text,'Sorry, promo code is failed to be applied. Please contact Us. Reason: ' . mysql_error());
                }
            }
        }
        else{
            $action['result'] = 'error';
            array_push($text,'Sorry, Failed to retrieve promotion details. Please contact Us.');
        }
        
        $action['text'] = $text;
    }*/
    
?>

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

//-->//-->
</script>

</head>

<body >
<div align="center">
    <table width="1000" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    </table>
    <form id="PROMOTIONMESSAGEform" name="PROMOTIONMESSAGEform" method="post" action="">
        <input type="hidden" name="pcode" id="pcode" value="<?php echo $_GET['pcode'] ?>"/>
        <input type="hidden" name="promoSubmit" id="promoSubmit" value="true"/>
  <table width="473" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
     
    <tr>
      <td height="355" colspan="2" align="center" valign="top" class="style1"><p>Promotion Fine Print :<br />
            <textarea name="fineprinttype" cols="80" rows="20"  class="style12"  style="border:none; resize:none;" id="fineprinttype"><?php echo $finePrint; ?></textarea>
          <br />
      </p></td>
    <tr>
        <td width="231" height="56" align="center" valign="middle"><input type="button" id="NotAcceptPromo" value="I don't Agree"  class="style1" readonly="" onclick="window.close()"/></td>
        <td width="242" align="center" valign="middle"><input type="button" id="AcceptPromo" value="I Accept Terms"  class="style1" readonly="" onclick="javascript:submitPromo();"/></td>
      </table>
</form>
</div>
</body>
    
 <script>
 function submitPromo() 
  {
    <?php   $_SESSION['pcode'] =  $_GET['pcode'];
            $_SESSION['promoSubmit'] =  TRUE;
    ?>  
     opener.location.reload(true);
     self.close();
  }
</script>
    
</html>

<?php
}
else{
    header("location: ../login.php");
}
?>
