$(document).ready(function(){
  //Use JQuery plugin-in jquery.ba-hashchange.js to resolve AJAX back button problem
  var flag = true;
  $(window).hashchange(function(){
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
  //convert month to 2 digits
  var month = (today.getMonth()+1).toString().length==1 ? '0'+(today.getMonth()+1) : today.getMonth()+1;
  $('#endDate').val(today.getFullYear() + '-' + (month) + '-' + today.getDate());
});
