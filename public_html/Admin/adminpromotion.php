<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

include_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Promotion.php';


if (session_id() == "") 
    session_start();

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    $promotion = new Promotion();
    
    if (isset($_POST['promotionA_x']) && isset($_POST['promotionA_y'])){       
        
        $newAdmins = array();
        preventSQLInjectionPromoA($promotion);
        
        $promotion->savePromotionAMaster();
        $promotion->savePromotionA();
        
    }
    else if (isset($_POST['promotionB_x']) && isset($_POST['promotionB_y'])){       
        
        $newAdmins = array();
        preventSQLInjectionPromoB($promotion);
        
        $promotion->savePromotionBMaster();
        $promotion->savePromotionB();
        
    }
    
    $promotion->fetchPromotionAMasterDetail();
    $promotion->fetchPromotionBMasterDetail();
    $promotion->fetchPromotionADetail();
    $promotion->fetchPromotionBDetail();
    
    $promotionsA = $promotion->getPromotionsA();
    $promotionsB = $promotion->getPromotionsB();
        
       
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
<title>Green Apple Mail  &copy; 2012 - PROMOTION</title>
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
<style type="text/css">
<!--
.style106 {color: #222641}
.style107 {color: #B4C2CB}
-->
</style>
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
          <td width="1000" height="104" valign="top"><iframe src="../Admin/adminHeader.php" width="1000" height="104" frameborder="0" scrolling="no" ></iframe></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="60" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="385" height="60" valign="top"><img src="../Images/AdminImages/GAMPageAdminPromotion.jpg" width="385" height="60" /></td>
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
          <form id="PROMOTIONsetupform" name="PROMOTIONsetupform" method="post" action="">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
       
          <td height="46" align="center" valign="middle" bgcolor="#B4C2CB" class="style3 style106" >PROMOTION TYPE A </td>
          </tr>
        
        <tr>
          <td height="66" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="170" height="66" align="right" valign="middle" class="style1">Activate Promotion </td>
                  <td width="68" align="right" valign="middle" class="style1">Type A </td>
                  <td width="43" align="center" valign="middle">
                    <label>
                        <input  type="Checkbox" name="activatedPromotionA" value="1"  <?php if ($promotion->getActivatedPromotionA() == 1) echo 'checked="checked"'; ?>   />
                    </label>              </td>
                  <td width="146" align="right" valign="middle" class="style1">Promo code : </td>
                  <td width="283" align="center" valign="middle">
                    <label>
                        <input name="codePromoA" type="text" class="style1" value="<?php echo $promotion->getCodePromoA(); ?>" size="30" />
                    </label>              </td>
                  <td width="248" align="center" valign="middle"><input name="promotionA" type="image"  src="../Images/GAMButtonSaveOFF.jpg" width="140" height="66" id="Image30" value="Submit" onmouseover="MM_swapImage('Image30','','../Images/GAMButtonSaveON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                </tr>
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="50" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="200" height="50" align="right" valign="top" class="style1">Select dates range from: </td>
                  <td width="200" align="center" valign="top">
                    <label>
                        <input name="validfrompromoA"  type="date"  class="style1" id="validfrompromoA" value="<?php echo $promotion->getValidfrompromoA(); ?>" size="20" />
                    </label>              </td>
                  <td width="40" align="center" valign="top" class="style1">TO</td>
                  <td width="200" align="center" valign="top"><input name="validtillpromoA"  type="date"  class="style1" id="validtillpromoA" value="<?php echo $promotion->getValidtillpromoA(); ?>" size="20" /></td>
                  <td width="26" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td width="292" align="left" valign="top" class="style1">12:00 AM server time <br />
                    format: YYYY-MM-DD </td>
                </tr>
            
            
            
            
          </table></td>
          </tr>
      
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input type="checkbox" name="typeARange1"  id="typeARange1" value="1" <?php if (!empty($promotionsA[1]) && $promotionsA[1]['activated'] == '1') echo 'checked="checked"'; ?>/>
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="fromunitsR1"  type="number"  class="style1" id="fromunitsR1" value="<?php if(!empty($promotionsA[1])){ echo $promotionsA[1]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="tounitsR1"  type="number"  class="style1" id="tounitsR1" value="<?php if(!empty($promotionsA[1])){ echo $promotionsA[1]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Discount : </td>
                  <td width="76" align="center" valign="middle"><input name="dsctR1"   type="number"  class="style1" id="dsctR1" value="<?php if(!empty($promotionsA[1])){ echo $promotionsA[1]['discount']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over final price. </td>
                </tr>
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input name="typeARange2" type="checkbox" id="typeARange2" value="1" <?php if (!empty($promotionsA[2]) && $promotionsA[2]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="fromunitsR2"  type="number"  class="style1" id="fromunitsR2" value="<?php if(!empty($promotionsA[2])){ echo $promotionsA[2]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="tounitsR2"  type="number"  class="style1" id="tounitsR2" value="<?php if(!empty($promotionsA[2])){ echo $promotionsA[2]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Discount : </td>
                  <td width="76" align="center" valign="middle"><input name="dsctR2"   type="number"  class="style1" id="dsctR2" value="<?php if(!empty($promotionsA[2])){ echo $promotionsA[2]['discount']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over final price. </td>
                </tr>
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input type="checkbox" name="typeARange3"  id="typeARange3" value="1" <?php if (!empty($promotionsA[3]) && $promotionsA[3]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="fromunitsR3"  type="number"  class="style1" id="fromunitsR3" value="<?php if(!empty($promotionsA[3])){ echo $promotionsA[3]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="tounitsR3"  type="number"  class="style1" id="tounitsR3" value="<?php if(!empty($promotionsA[3])){ echo $promotionsA[3]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Discount : </td>
                  <td width="76" align="center" valign="middle"><input name="dsctR3"   type="number"  class="style1" id="dsctR3" value="<?php if(!empty($promotionsA[3])){ echo $promotionsA[3]['discount']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over final price. </td>
                </tr>
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input type="checkbox" name="typeARange4" id="typeARange4" value="1" <?php if (!empty($promotionsA[4]) && $promotionsA[4]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="fromunitsR4"  type="number"  class="style1" id="fromunitsR4" value="<?php if(!empty($promotionsA[4])){ echo $promotionsA[4]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="tounitsR4"  type="number"  class="style1" id="tounitsR4" value="<?php if(!empty($promotionsA[4])){ echo $promotionsA[4]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Discount : </td>
                  <td width="76" align="center" valign="middle"><input name="dsctR4"   type="number"  class="style1" id="dsctR4" value="<?php if(!empty($promotionsA[4])){ echo $promotionsA[4]['discount']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over final price. </td>
			</tr>
			</table>
			</td>
			</tr>

		  <tr>
                      
<tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input type="checkbox" name="typeARange5"  id="typeARange5" value="1" <?php if (!empty($promotionsA[5]) && $promotionsA[5]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="fromunitsR5"  type="number"  class="style1" id="fromunitsR5" value="<?php if(!empty($promotionsA[5])){ echo $promotionsA[5]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="tounitsR5"  type="number"  class="style1" id="tounitsR5" value="<?php if(!empty($promotionsA[5])){ echo $promotionsA[5]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Discount : </td>
                  <td width="76" align="center" valign="middle"><input name="dsctR5"   type="number"  class="style1" id="dsctR5" value="<?php if(!empty($promotionsA[5])){ echo $promotionsA[5]['discount']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over final price. </td>
                </tr>
            
            
            
            </table></td>
          </tr>
        <tr>                      
          <td height="164" valign="top" >
		  <table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
          <td width="141" height="56" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">Promo A <br />
                Fine print : 
				
		   </td>
                      <td width="817" height="164" align="center" valign="middle" bgcolor="#FFFFFF"><textarea name="fineprintpromoA" cols="160" rows="9" class="style12" id="fineprintpromoA"><?php echo $promotion->getFineprintpromoA(); ?>
                      </textarea></td>
            </tr>
            </table>
			</td>
			</tr>
      
	  
            </table>  
		 
		 
		 
		 
		 
		 <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
       
          <td width="958" height="30" align="center" valign="middle" bgcolor="#B4C2CB" class="style3 style107" ><span class="style106">PROMOTION TYPE B</span> </td>
          </tr>
		  <tr>
          <td height="30" align="center" valign="bottom" bgcolor="#FFFFFF" class="style3" >
		  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="170" height="66" align="right" valign="middle" class="style1">Activate Promotion </td>
                  <td width="68" align="right" valign="middle" class="style1">Type B </td>
                  <td width="43" align="center" valign="middle"><input  type="Checkbox" name="activatedPromotionB" value="1" <?php if ($promotion->getActivatedPromotionB() == 1) echo 'checked="checked"'; ?>  /></td>
                  <td width="146" align="right" valign="middle" class="style1">Promo code : </td>
                  <td width="289" align="center" valign="middle">
                    <label>
                        <input name="codePromoB" type="text" class="style1" value="<?php echo $promotion->getCodePromoB(); ?>" size="30" />
                    </label>              </td>
                  <td width="242" align="center" valign="middle"><input name="promotionB" type="image"  src="../Images/GAMButtonSaveOFF.jpg" width="140" height="66" id="Image31" value="Submit" onmouseover="MM_swapImage('Image31','','../Images/GAMButtonSaveON.jpg',1)" onmouseout="MM_swapImgRestore()" /></td>
                </tr>
            </table>	  		  </td>
          </tr>
                <tr>
          <td height="50" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="200" height="50" align="right" valign="top" class="style1">Select dates range from: </td>
                  <td width="200" align="center" valign="top">
                    <label>
                        <input name="validfrompromoB"  type="date"  class="style1" id="promostarts" value="<?php echo $promotion->getValidfrompromoB(); ?>" size="20" />
                    </label>              </td>
                  <td width="40" align="center" valign="top" class="style1">TO</td>
                  <td width="200" align="center" valign="top"><input name="validtillpromoB"  type="date"  class="style1" id="promoends" value="<?php echo $promotion->getValidtillpromoB(); ?>" size="20" /></td>
                  <td width="26" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td width="292" align="left" valign="top" class="style1">12:00 AM server time <br />
                    format: YYYY-MM-DD </td>
                </tr>
            
            
            
            
          </table></td>
          </tr>
		
		
		
		<tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input name="typeBRange1" type="checkbox" id="typeBRange1" value="1" <?php if (!empty($promotionsB[1]) && $promotionsB[1]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="minunitsR1"  type="number"  class="style1" id="minunitsR1" value="<?php if(!empty($promotionsB[1])){ echo $promotionsB[1]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="maxunitsR1"  type="number"  class="style1" id="maxunitsR1" value="<?php if(!empty($promotionsB[1])){ echo $promotionsB[1]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Demo file  : </td>
                  <td width="76" align="center" valign="middle"><input name="demofiletR1"   type="number"  class="style1" id="demofiletR1" value="<?php if(!empty($promotionsB[1])){ echo $promotionsB[1]['demofile']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over total units. </td>
                </tr>
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input name="typeBRange2" type="checkbox" id="typeBRange2" value="1" <?php if (!empty($promotionsB[2]) && $promotionsB[2]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="minunitsR2"  type="number"  class="style1" id="minunitsR2" value="<?php if(!empty($promotionsB[2])){ echo $promotionsB[2]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="maxunitsR2"  type="number"  class="style1" id="maxunitsR2" value="<?php if(!empty($promotionsB[2])){ echo $promotionsB[2]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Demo file  : </td>
                  <td width="76" align="center" valign="middle"><input name="demofiletR2"   type="number"  class="style1" id="demofiletR2" value="<?php if(!empty($promotionsB[2])){ echo $promotionsB[2]['demofile']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over total units. </td>
                </tr>
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input name="typeBRange3" type="checkbox" id="typeBRange3" value="1" <?php if (!empty($promotionsB[3]) && $promotionsB[3]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="minunitsR3"  type="number"  class="style1" id="minunitsR3" value="<?php if(!empty($promotionsB[3])){ echo $promotionsB[3]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="maxunitsR3"  type="number"  class="style1" id="maxunitsR3" value="<?php if(!empty($promotionsB[3])){ echo $promotionsB[3]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Demo file  : </td>
                  <td width="76" align="center" valign="middle"><input name="demofiletR3"   type="number"  class="style1" id="demofiletR3" value="<?php if(!empty($promotionsB[3])){ echo $promotionsB[3]['demofile']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over total units. </td>
                </tr>
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input name="typeBRange4" type="checkbox" id="typeBRange4" value="1" <?php if (!empty($promotionsB[4]) && $promotionsB[4]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="minunitsR4"  type="number"  class="style1" id="minunitsR4" value="<?php if(!empty($promotionsB[4])){ echo $promotionsB[4]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="maxunitsR4"  type="number"  class="style1" id="maxunitsR4" value="<?php if(!empty($promotionsB[4])){ echo $promotionsB[4]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Demo file  : </td>
                  <td width="76" align="center" valign="middle"><input name="demofiletR4"   type="number"  class="style1" id="demofiletR4" value="<?php if(!empty($promotionsB[4])){ echo $promotionsB[4]['demofile']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over total units. </td>
                </tr>
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="41" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="74" height="41" align="center" valign="middle" class="style1">Activate</td>
                  <td width="36" align="center" valign="middle">
                    <label>
                    <input name="typeBRange5" type="checkbox" id="typeBRange5" value="1" <?php if (!empty($promotionsB[5]) && $promotionsB[5]['activated'] == '1') echo 'checked="checked"'; ?> />
                  </label>              </td>
                  <td width="97" align="right" valign="middle" class="style1">Units range : </td>
                  <td width="198" align="center" valign="middle"><input name="minunitsR5"  type="number"  class="style1" id="minunitsR5" value="<?php if(!empty($promotionsB[5])){ echo $promotionsB[5]['unitrangefrom']; } ?>" size="20" /></td>
                  <td width="38" align="center" valign="middle" class="style1">to</td>
                  <td width="179" align="center" valign="middle"><input name="maxunitsR5"  type="number"  class="style1" id="maxunitsR5" value="<?php if(!empty($promotionsB[5])){ echo $promotionsB[5]['unitrangetill']; } ?>" size="20" /></td>
                  <td width="80" align="right" valign="middle" class="style1">Demo file  : </td>
                  <td width="76" align="center" valign="middle"><input name="demofiletR5"   type="number"  class="style1" id="demofiletR5" value="<?php if(!empty($promotionsB[5])){ echo $promotionsB[5]['demofile']; } ?>" size="6" /></td>
                  <td width="180" align="left" valign="middle" class="style1">% over total units. </td>
                </tr>
            </table></td>
          </tr>
        <tr>
          <td height="131" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="141" height="131" align="center" valign="middle" bgcolor="#FFFFFF" class="style1">Promo B <br />
                Fine print : </td>
                 </td>
                      <td width="817" height="164" align="center" valign="middle" bgcolor="#FFFFFF"><textarea name="fineprintpromoB" cols="160" rows="9" class="style12" id="fineprintpromoB"><?php echo $promotion->getFineprintpromoB(); ?>
                      </textarea></td>
                  </tr>
            
            
            
            </table></td>
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
      <td height="496"></td>
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

function preventSQLInjectionPromoA(Promotion $promotion){
    $promotionsB = array();
    $activatedPromotionA = 0;
    
    if (isset($_POST['activatedPromotionA'])){
        $activatedPromotionA = mysql_real_escape_string($_POST['activatedPromotionA']);   
    }
    
    $codePromoA = mysql_real_escape_string($_POST['codePromoA']);
    $validfrompromoA = mysql_real_escape_string($_POST['validfrompromoA']);
    $validtillpromoA = mysql_real_escape_string($_POST['validtillpromoA']);
    $fineprintpromoA = mysql_real_escape_string($_POST['fineprintpromoA']);
    
    if (!empty($_POST['typeARange1'])) $promoAActivate = mysql_real_escape_string($_POST['typeARange1']); else $promoAActivate = 0;
    $promoAunitfrom = mysql_real_escape_string($_POST['fromunitsR1']);
    $promoAunitto = mysql_real_escape_string($_POST['tounitsR1']);
    $promoAdiscount = mysql_real_escape_string($_POST['dsctR1']);
    
    if (!empty($promoAunitfrom) && !empty($promoAunitto) && !empty($promoAdiscount)){
        $record = array(
                'unitrangefrom' => $promoAunitfrom,
                'unitrangetill' => $promoAunitto,
                'discount' => $promoAdiscount,
                'activated' => $promoAActivate
               );
        $promotionsA[1] = $record;
    }
    
    if (!empty($_POST['typeARange2'])) $promoAActivate = mysql_real_escape_string($_POST['typeARange2']); else $promoAActivate = 0;
    $promoAunitfrom = mysql_real_escape_string($_POST['fromunitsR2']);
    $promoAunitto = mysql_real_escape_string($_POST['tounitsR2']);
    $promoAdiscount = mysql_real_escape_string($_POST['dsctR2']);
    
    if (!empty($promoAunitfrom) && !empty($promoAunitto) && !empty($promoAdiscount)){
        $record = array(
                'unitrangefrom' => $promoAunitfrom,
                'unitrangetill' => $promoAunitto,
                'discount' => $promoAdiscount,
                'activated' => $promoAActivate
               );
        $promotionsA[2] = $record;
    }
    
    if (!empty($_POST['typeARange3'])) $promoAActivate = mysql_real_escape_string($_POST['typeARange3']); else $promoAActivate = 0;
    $promoAunitfrom = mysql_real_escape_string($_POST['fromunitsR3']);
    $promoAunitto = mysql_real_escape_string($_POST['tounitsR3']);
    $promoAdiscount = mysql_real_escape_string($_POST['dsctR3']);
    
    if (!empty($promoAunitfrom) && !empty($promoAunitto) && !empty($promoAdiscount)){
        $record = array(
                'unitrangefrom' => $promoAunitfrom,
                'unitrangetill' => $promoAunitto,
                'discount' => $promoAdiscount,
                'activated' => $promoAActivate
               );
        $promotionsA[3] = $record;
    }
    
    if (!empty($_POST['typeARange4'])) $promoAActivate = mysql_real_escape_string($_POST['typeARange4']); else $promoAActivate = 0;
    $promoAunitfrom = mysql_real_escape_string($_POST['fromunitsR4']);
    $promoAunitto = mysql_real_escape_string($_POST['tounitsR4']);
    $promoAdiscount = mysql_real_escape_string($_POST['dsctR4']);
    
    if (!empty($promoAunitfrom) && !empty($promoAunitto) && !empty($promoAdiscount)){
        $record = array(
                'unitrangefrom' => $promoAunitfrom,
                'unitrangetill' => $promoAunitto,
                'discount' => $promoAdiscount,
                'activated' => $promoAActivate
               );
        $promotionsA[4] = $record;
    }
    
    if (!empty($_POST['typeARange5'])) $promoAActivate = mysql_real_escape_string($_POST['typeARange5']); else $promoAActivate = 0;
    $promoAunitfrom = mysql_real_escape_string($_POST['fromunitsR5']);
    $promoAunitto = mysql_real_escape_string($_POST['tounitsR5']);
    $promoAdiscount = mysql_real_escape_string($_POST['dsctR5']);
    
    if (!empty($promoAunitfrom) && !empty($promoAunitto) && !empty($promoAdiscount)){
        $record = array(
                'unitrangefrom' => $promoAunitfrom,
                'unitrangetill' => $promoAunitto,
                'discount' => $promoAdiscount,
                'activated' => $promoAActivate
               );
        $promotionsA[5] = $record;
    }
    
    
    $promotion->setActivatedPromotionA($activatedPromotionA);
    $promotion->setCodePromoA($codePromoA);
    $promotion->setValidfrompromoA($validfrompromoA);
    $promotion->setValidtillpromoA($validtillpromoA);
    $promotion->setFineprintpromoA($fineprintpromoA);
    $promotion->setPromotionsA($promotionsA);
     
 }
 
 function preventSQLInjectionPromoB(Promotion $promotion){
    $promotionsB = array();
    $activatedPromotionB = 0;
    
    if (isset($_POST['activatedPromotionB'])){
        $activatedPromotionB = mysql_real_escape_string($_POST['activatedPromotionB']);   
    }
    
    $codePromoB = mysql_real_escape_string($_POST['codePromoB']);
    $validfrompromoB = mysql_real_escape_string($_POST['validfrompromoB']);
    $validtillpromoB = mysql_real_escape_string($_POST['validtillpromoB']);
    $fineprintpromoB = mysql_real_escape_string($_POST['fineprintpromoB']);
    
    
    if (!empty($_POST['typeBRange1'])) $promoBActivate = mysql_real_escape_string($_POST['typeBRange1']); else $promoBActivate = 0;
    $promoBunitfrom = mysql_real_escape_string($_POST['minunitsR1']);
    $promoBunitto = mysql_real_escape_string($_POST['maxunitsR1']);
    $promoBDemo = mysql_real_escape_string($_POST['demofiletR1']);
    
    if (!empty($promoBunitfrom) && !empty($promoBunitto) && !empty($promoBDemo)){
        $record = array(
                'unitrangefrom' => $promoBunitfrom,
                'unitrangetill' => $promoBunitto,
                'demofile' => $promoBDemo,
                'activated' => $promoBActivate
               );
        $promotionsB[1] = $record;
    }
    
    if (!empty($_POST['typeBRange2'])) $promoBActivate = mysql_real_escape_string($_POST['typeBRange2']); else $promoBActivate = 0;
    $promoBunitfrom = mysql_real_escape_string($_POST['minunitsR2']);
    $promoBunitto = mysql_real_escape_string($_POST['maxunitsR2']);
    $promoBDemo = mysql_real_escape_string($_POST['demofiletR2']);
    
    if (!empty($promoBunitfrom) && !empty($promoBunitto) && !empty($promoBDemo)){
        $record = array(
                'unitrangefrom' => $promoBunitfrom,
                'unitrangetill' => $promoBunitto,
                'demofile' => $promoBDemo,
                'activated' => $promoBActivate
               );
        $promotionsB[2] = $record;
    }
    
    if (!empty($_POST['typeBRange3'])) $promoBActivate = mysql_real_escape_string($_POST['typeBRange3']); else $promoBActivate = 0;
    $promoBunitfrom = mysql_real_escape_string($_POST['minunitsR3']);
    $promoBunitto = mysql_real_escape_string($_POST['maxunitsR3']);
    $promoBDemo = mysql_real_escape_string($_POST['demofiletR3']);
    
    if (!empty($promoBunitfrom) && !empty($promoBunitto) && !empty($promoBDemo)){
        $record = array(
                'unitrangefrom' => $promoBunitfrom,
                'unitrangetill' => $promoBunitto,
                'demofile' => $promoBDemo,
                'activated' => $promoBActivate
               );
        $promotionsB[3] = $record;
    }
    
    if (!empty($_POST['typeBRange4'])) $promoBActivate = mysql_real_escape_string($_POST['typeBRange4']); else $promoBActivate = 0;
    $promoBunitfrom = mysql_real_escape_string($_POST['minunitsR4']);
    $promoBunitto = mysql_real_escape_string($_POST['maxunitsR4']);
    $promoBDemo = mysql_real_escape_string($_POST['demofiletR4']);
    
    if (!empty($promoBunitfrom) && !empty($promoBunitto) && !empty($promoBDemo)){
        $record = array(
                'unitrangefrom' => $promoBunitfrom,
                'unitrangetill' => $promoBunitto,
                'demofile' => $promoBDemo,
                'activated' => $promoBActivate
               );
        $promotionsB[4] = $record;
    }
    
    if (!empty($_POST['typeBRange5'])) $promoBActivate = mysql_real_escape_string($_POST['typeBRange5']); else $promoBActivate = 0;
    $promoBunitfrom = mysql_real_escape_string($_POST['minunitsR5']);
    $promoBunitto = mysql_real_escape_string($_POST['maxunitsR5']);
    $promoBDemo = mysql_real_escape_string($_POST['demofiletR5']);
    
    if (!empty($promoBunitfrom) && !empty($promoBunitto) && !empty($promoBDemo)){
        $record = array(
                'unitrangefrom' => $promoBunitfrom,
                'unitrangetill' => $promoBunitto,
                'demofile' => $promoBDemo,
                'activated' => $promoBActivate
               );
        $promotionsB[5] = $record;
    }
    
    $promotion->setActivatedPromotionB($activatedPromotionB);
    $promotion->setCodePromoB($codePromoB);
    $promotion->setValidfrompromoB($validfrompromoB);
    $promotion->setValidtillpromoB($validtillpromoB);
    $promotion->setFineprintpromoB($fineprintpromoB);
    $promotion->setPromotionsB($promotionsB);
     
 }

 
 
?>