function readOnly(){
  var oElements = document.getElementsByTagName("input");
  for(i=0; i<oElements.length;i++) {
    if (oElements[i].type=='checkbox') {var x=oElements[i];x.onclick=function x() {return false;}}
    if (oElements[i].type=='radio' && oElements[i].checked==false) {oElements[i].disabled=true;}
    if (oElements[i].type=='text') {oElements[i].readOnly=true;}
  }
  oElements = document.getElementsByTagName("select");
  for(i=0; i<oElements.length;i++) {
    oElements[i].disabled=true;
  }
  oElements = document.getElementsByTagName("textarea");
  for(i=0; i<oElements.length;i++) {
    oElements[i].disabled=true;
  }
}
