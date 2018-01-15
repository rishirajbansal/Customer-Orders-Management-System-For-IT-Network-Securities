// JavaScript Document


if (document.getElementById) {
  document.write('<style type="text/css">.texter {display:none; border-left:#FFFFFF 20px solid; border-right:#FFFFFF 20px solid; color:#666666; font: 14px Arial, Helvetica, sans-serif; margin-bottom: 12px;}</style>') }

  var divNum = new Array("a1","a2","a3","a4","a5","a6","a7","a8","a9","a10","a11","a12","a13","a14","a15");
  
  
function openClose(theID) {
  for(var i=0; i < divNum.length; i++) {
    if (divNum[i] == theID) {
      if (document.getElementById(divNum[i]).style.display == "block") { document.getElementById(divNum[i]).style.display = "none" }
      else {
        document.getElementById(divNum[i]).style.display = "block"
      }
  } else {
    document.getElementById(divNum[i]).style.display = "none"; }
  }
}
