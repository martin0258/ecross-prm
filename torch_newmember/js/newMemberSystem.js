/*Konami 密技:)*/
if ( window.addEventListener ) {
  //上上下下左右左右jesus
  //var kkeys = [], konami = "38,38,40,40,37,39,37,39,74,69,83,85,83";
  var kkeys = [], konami = "38,40,37,39,84,79,82,67,72";
  window.addEventListener("keydown", function(e){
    kkeys.push( e.keyCode );
    if ( kkeys.toString().indexOf( konami ) >= 0 ){
      kkeys = [];
      window.location = "http://www.torchchurch.com";
    }
  }, false);
}

/*產生日期下拉選單*/
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

/*將表單頁面變成唯讀*/
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

/*設置查詢介面的JS
 * 處理hash以解決回上一頁JQUERY的值跑掉的問題(目前只處理只有小組選單)
 * input text套用CSS樣式
 * datepicker調整*/
$(document).ready(function(){
  //Use JQuery plugin-in jquery.ba-hashchange.js to resolve AJAX back button problem
  var flag = true;
  $(window).hashchange( function(){
    if(flag)$('#group_select1').trigger('change');
  });

  $('form').submit(function(){
    flag = false;
    var groupID = $('#group_select2').val();
    window.location.hash=groupID;
    return true;
  });
  $(window).hashchange();

  $(':text').addClass('text');

  $('.datepicker').datepicker({
    showAnim: 'show',
    dateFormat: 'yy-mm-dd',
    showOn: 'both',
    buttonImageOnly: false
  }); 
  var today = new Date();
  $('#endDate').val(today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate());
});

/*表單Focus的選項變色*/
$(document).ready(function(){
  var li_focusout=null;
  $('li:not(.button)').focusin(function() { 
    if($(this) != li_focusout){
      if(li_focusout != null)li_focusout.removeClass('focused');
      $(this).addClass('focused');
    }
  });
  $('li:not(.button)').focusout(function() { 
    li_focusout = $(this); 
  });
  $(':checkbox').click(function() {
    if(li_focusout != null)li_focusout.removeClass('focused');
    li_focusout = $(this).parent().parent();
    $(this).parent().parent().addClass('focused');
  });
});

/*為checkbox勾選的列上色*/
$(document).ready(function(){
  $(":checkbox:not('#checkAll')").click(function(){
    $(this).parent().parent().toggleClass('checkedRow');
  });
});

/*判斷幾個checkbox被勾選*/
function countChecked(action){
  var count = 0;
  $(":checkbox:not('#checkAll')").each(
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

/*階層式下拉選單: 小組、地址*/
$(document).ready(function () {

  function DropDown2(sLevel2, sName, sValue, sHiddenID, sDefaultOption){
    //Class constructor
    this.level2 = sLevel2;
    this.name = sName;
    this.value = sValue;
    this.hiddenID = sHiddenID;
    this.defaultOption = sDefaultOption;
  };

  //AJAX callback function
  function callBack(ddObj){
    eval("jsonObj = {'" + ddObj.name + "' : '" + ddObj.value + "' }");
    // 觸發第二階下拉式選單
    $(ddObj.level2).removeOption(/.?/).ajaxAddOption(
      'DDLaction.php', 
      jsonObj,
      false, 
      function () {
        // 設定預設選項
        var groupID = window.location.hash;
        groupID = groupID.replace(/^.*#/, '');
        groupID = (groupID != "") ? groupID : $(ddObj.hiddenID).val();
        $(this).selectOptions(groupID).trigger('change');
      }
    ).addOption("", ddObj.defaultOption);
  }

  //$.history.init(callBack);

  // 地址關聯下拉式選單
  $('#address_select1').change(function () {
    var dd2Obj = new DropDown2('#address_select2', 'zipCountry', $(this).val(), 
      '#zipCode', '請選擇鄉鎮市區');
    //$.history.load(dd2Obj);
    callBack(dd2Obj);
  });

  // 小組關聯下拉式選單
  $('#group_select1').change(function () {
    var dd2Obj = new DropDown2('#group_select2', 'category', $(this).val(), 
      '#groupID', '請選擇小組');
    //$.history.load(dd2Obj);
    callBack(dd2Obj);
  });
});
