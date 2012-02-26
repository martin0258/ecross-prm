$(document).ready(function(){
  /*為勾選的列上色*/
  $("input:checkbox:not('#checkAll')").click(function(){
    $(this).parent().parent().toggleClass('checkedRow');
  });
});
/*判斷幾個checkbox被勾選*/
function countChecked(action){
  var count = 0;
  $("input:checkbox:not('#checkAll')").each(
      function(){if($(this).attr("checked"))count++;}
      );

  if(count==0){
    window.alert("尚未勾選資料");
    return false;
  }

  var sure = window.confirm("確定" + action +"這"+ count +"筆資料？");
  if(sure)return true;
  else return false; 
}

/*checkbox全選*/
function checkAllAction(){
  if($("#checkAll").attr("checked"))
  {
    $("input:checkbox:not('#checkAll')").each(function() {
      if(!$(this).attr('checked')){
        $(this).trigger('click');
      }
    });
  }
  else
  {
    $("input:checkbox:not('#checkAll')").each(function() {
      if($(this).attr('checked')){
        $(this).trigger('click');
      }
    });          
  }
}
