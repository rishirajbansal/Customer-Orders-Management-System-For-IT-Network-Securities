<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Just pay for the records you need to validate. 
It’s that simple… No setup costs; no monthly, quarterly or annual fees; no contracts; no strings attached.">
<meta name="keywords" content="Pricing, Email, validation, cleans, hygiene, invalid, valid, messages, mailboxes, cleanse, lists, undeliverable, validator, verification, verifier, emails, Email Validation Leaders, Green, Mail">
<title>Green Apple Mail  &copy; 2012 - Pricing</title>
<link type="text/css" rel="stylesheet" href="css/GAMmain.css">
<script type="text/JavaScript" src="js/common.js"></script>
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
.style100 {
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	margin-right: 20px;
	margin-left: 20px;
}
.style101 {color: #FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size: 14px; margin-right: 20px; margin-left: 20px; }
.style103 {font-family: Arial, Helvetica, sans-serif; font-size: 18px; margin-right: 20px; margin-left: 20px; color: #FFFFFF;}
.style104 {font-size: 19px}
.style3 {
    font-size: 18px;
	font-family: Arial, Helvetica, sans-serif;
    color: #92B5DF;
	font-weight: bold;
	margin-right: 20px;
	margin-left: 20px;
	}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">

var max_units = 9000000; 
var currency = "$";
//var cents = "cents";
var cents = "";

function getDiscountPrice(units) {
if (units >= max_units) return 4.89;
if (units >= 2000000) return 4.93;
if (units >= 1500001) return 4.93; 
if (units >= 1500000) return 5.8;
if (units >= 1000001) return 5.8;
if (units >= 1000000) return 6.8;
if (units >= 750001) return 6.8;
if (units >= 750000) return 7.74;
if (units >= 500001) return 7.74;
if (units >= 500000) return 9.76;
if (units >= 250001) return 9.76;
if (units >= 250000) return 15.49;
if (units >= 75001) return 15.49;
if (units >= 75000) return 29.64;
if (units >= 30001) return 29.64;
if (units >= 30000) return 37.75;
if (units >= 20001) return 37.75;
if (units >= 20000) return 51.52;
if (units >= 10001) return 51.52;
if (units >= 10000) return 92.94;
if (units >= 101) return 92.94;
if (units == 0) return 0;
}

function getComodin(units) {
if (units >= max_units) return 999;
if (units >= 2000000) return 989;
if (units >= 1500001) return 989; 
if (units >= 1500000) return 840;
if (units >= 1000001) return 840;
if (units >= 1000000) return 715;
if (units >= 750001) return 715;
if (units >= 750000) return 619;
if (units >= 500001) return 619;
if (units >= 500000) return 513;
if (units >= 250001) return 513;
if (units >= 250000) return 369;
if (units >= 75001) return 369;
if (units >= 75000) return 230;
if (units >= 30001) return 230;
if (units >= 30000) return 170;
if (units >= 20001) return 170;
if (units >= 20000) return 137;
if (units >= 10001) return 137;
if (units >= 10000) return 90;
if (units >= 1000) return 90;
if (units >= 101) return 90;
if (units == 0) return 0;
}

function getNumberOfUnits() {
var units = document.calculator.units.value; 
units = units.replace(",","");
return (units == "") ? 0 : units;
}

function formatNumberOfUnits() {
document.calculator.units.value = number_format(document.calculator.units.value, '', '',',');
}

function showResult(result) {
document.calculator.amount.value = result;	
}

function formatMessage(units, unit_price, unit_coco) {
/*return currency + formatPrice((units * (unit_price/10000)) + unit_coco);*/
return formatPrice((units * (unit_price/10000)) + unit_coco);
}

function formatPricing(price){
result = number_format(price, 2, '.', ',');
result = currency + result + ' ' + cents;
return result;
}

function contactMessage(units, unit_price, unit_coco) {
return "contact us";
}

function getAltUnits(units) {
var discount_price = getDiscountPrice(units);
if (units < max_units) do { units++ } while (discount_price == getDiscountPrice(units));
return units;
}

function findPrice() {
var units = getNumberOfUnits();
var unit_price = getDiscountPrice(units);
var alt_units = getAltUnits(units);
var unit_coco = getComodin(units);
var alt_unit_price = getDiscountPrice(alt_units);
var result;
if (units >= 2000001) {result = contactMessage(units, unit_price,unit_coco);}
else if (units >= 101){ result = formatPricing(formatMessage(units, unit_price,unit_coco));} 
else if (units == 0){ result = formatPricing(0.00);} 
else {result = formatPricing(80.00);}
showResult(result);
}

function formatPrice(value) {
var result= Math.floor(value) + ".";
var cents = 10 * (value-Math.floor(value)) + 0.50;
result += Math.floor(cents / 10);
result += Math.floor(cents % 10);
return result;
}

function filterNonNumeric(field) {
var result = new String();
var numbers = "0123456789";
var chars = field.value.split("");  
for (i = 0; i < chars.length; i++) {
if (numbers.indexOf(chars[i]) != -1) result += chars[i];
}
if (field.value != result) field.value = result;
}


//Turnaround code

function getTurnaround(units) {
if (units >= max_units) return 9;
if (units >= 2000000) return 7; 
if (units >= 1000000) return 5;
if (units >= 850000) return 4;
if (units >= 450000) return 3;
if (units >= 200000) return 2;
if (units >= 50000) return 1;
if (units >= 1) return 1;
if (units == 0) return 1;
}

function showTURNResult(result) {
document.calculator.turnaround.value = result;	
}

function formatTURNMessage(units, turn_units) {
return getTurnaround(units)*1;
}

function contactTURNMessage(units, turn_units) {
return "contact us";
}

function findTURNPrice() {
var units = getNumberOfUnits();
var turn_units = getTurnaround(units);
var result;
if (units >= 2000001)
{result = contactTURNMessage(units, turn_units);}
else if (units >= 1){
result = formatTURNMessage(units, turn_units);} 
else
{result = (0);}
showTURNResult(result);
}


</script>
<style type="text/css">
<!--
.style108 {
	font-size: 18px;
	color: #333333;
}
-->
</style>
</head>

<body onLoad="findPrice()">
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
          <td width="385" height="60" valign="top"><img src="Images/GAMPagePricing.jpg" width="385" height="60" /></td>
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
        <tr>
          <td width="958" height="50" valign="top" bgcolor="#FFFFFF" ><h1 align="justify" class="style1 style108">Just pay for the records you need to validate! <br />
            It&rsquo;s that simple&hellip; No setup costs. No monthly, quarterly or annual fees. No contracts. No strings attached! </h1></td>
          </tr>
        <tr>
          <td height="316" align="center" valign="middle" bgcolor="#FFFFFF" ><div align="center"><img src="Images/PGuidelineGfx.jpg" width="807" height="294" /> </div></td>
          </tr>
        <tr>
          <td height="31" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style1">The more email addresses your file contains, the lower our price per-record-validated.
            </p></td>
          </tr>
        <tr>
          <td height="124" valign="top" bgcolor="#FFFFFF" >
             <table width="958" height="100" border="0" cellpadding="0"  cellspacing="0" bgcolor="#FFFFFF">
               <!--DWLayoutTable-->
               <form name=calculator>
                 <tr> 
                   <td width="79" height="30">&nbsp;</td>
                    <td width="244" valign="top" bgcolor="#222640"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td colspan="3" align="center" valign="middle" bgcolor="#222640"><p class="style103 style104">Price Calculator</p></td>
                    <td width="285" valign="top" bgcolor="#222640"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td width="74">&nbsp;</td>
                  </tr>
                 
                 <tr>
                   <td height="30"></td>
                    <td colspan="2" valign="top" bgcolor="#222640"><p align="right" class="style101">Number of emails:  </p></td>
                    <td width="226" align="center" valign="middle" bgcolor="#222640"><input name="units" type="text"   align="absmiddle" class="style100" onkeypress="findPrice(); findTURNPrice()" onkeydown="findPrice(); findTURNPrice()" onkeyup="filterNonNumeric(this); findPrice(); findTURNPrice();formatNumberOfUnits();" value="1" size="24" /></td>
                    <td colspan="2" valign="top" bgcolor="#222640"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                 <tr>
                   <td height="32"></td>
	                <td colspan="2" valign="top" bgcolor="#222640"><p align="right" class="style101">Total price: </p></td>
                    <td align="center" valign="middle" bgcolor="#222640"><input name="amount"  height="20" type="text" class="style100" style="border:0;" onfocus="This.blur()" size="24" readonly=""/></td>
                    <td colspan="2" valign="top" bgcolor="#222640"><p align="left" class="style101">Amount expressed in US dollars. </p></td>
                    <td>&nbsp;</td>
                  </tr>
                 
  	            <tr>
	                <td height="32"></td>
	                <td colspan="2" valign="top" bgcolor="#222640"><p align="right" class="style101">Estimated processing time in days: </p></td>
                    <td align="center" valign="middle" bgcolor="#222640"><input name="turnaround" type="text" height="20" class="style100" style="border:0;" onfocus="This.blur()" size="24" readonly=""/></td>
                    <td colspan="2" valign="top" bgcolor="#222640"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td>&nbsp;</td>
	              </tr>
                 <tr>
                   <td height="0"></td>
	                <td></td>
	                <td></td>
	                <td></td>
	                <td width="22"></td>
	                <td></td>
	                <td></td>
                  </tr>
                 <tr>
                   <td height="1"></td>
	                <td></td>
	                <td width="28"></td>
	                <td></td>
	                <td></td>
	                <td></td>
	                <td></td>
                  </tr>
  	            </form>
            </table></td>
          </tr>
        <tr>
          <td height="51" valign="top" bgcolor="#FFFFFF" ><p align="justify" class="style3"><br />
            Which payment methods are supported? </p>
            <p align="justify" class="style1">We accept ALL major credit cards and debit cards or bank cards as well as any other methods of payment accepted by PayPal. </p>
            <p align="justify" class="style1">Please note that it is possible to use credit cards via PayPal even if you do not have a PayPal account and there is no surcharge for using credit cards to make purchases <br />
              <br />
            <a href="signup.html" target="_top">
            Sign up</a> with Green Apple Mail today and start validating your lists of email addresses! <a href="signup.html" target="_top">Signing-up</a> takes less than 30 seconds.<br />
            <br />
            </p>
			</td>
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
      <td height="718"></td>
      <td></td>
    </tr>
    <tr>
      <td height="160"></td>
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
