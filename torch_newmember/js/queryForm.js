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
  $('#endDate').datepicker('setDate', new Date());
});
