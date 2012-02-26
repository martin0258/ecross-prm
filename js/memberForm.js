/*階層式下拉選單: 用於小組和地址*/
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

/*表單Focus選項變色*/
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
  $(':checkbox, :radio').click(function() {
    if(li_focusout != null)li_focusout.removeClass('focused');
    li_focusout = $(this).closest('li');
    $(li_focusout).addClass('focused');
  });
});

$(document).ready(function(){
  //editMember.php，載入後觸發cascading ddl，跳到正確的預設選項
  $('#group_select1').trigger('change');
  $('#address_select1').trigger('change');

  $('#form1').validationEngine();
  $(':text').addClass('text');
  $('.datepicker').datepicker({
    showAnim: 'show',
    dateFormat: 'yy-mm-dd',
    showOn: 'both',
    buttonImageOnly: false
  });   
});
