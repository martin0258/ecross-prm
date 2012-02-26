function selectYear(year, start){
  var d = new Date();
  for(i=(start==null)?d.getFullYear()-1:start;i<=d.getFullYear();i++){
    if(year==i)
      document.write("<option value=" + i + " selected>" + i );
    else
      document.write("<option value=" + i + ">" + i );
  }
}
function selectMonth(month){
  for(i=1;i<=12;i++){
    if(month==i)
      document.write("<option value=" + i + " selected>" + i );
    else
      document.write("<option value=" + i + ">" + i );
  }
}
function selectDay(day){
  for(i=1;i<=31;i++){
    if(day==i)
      document.write("<option value=" + i + " selected>" + i );
    else
      document.write("<option value=" + i + ">" + i );
  }
}
