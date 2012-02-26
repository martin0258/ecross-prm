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
