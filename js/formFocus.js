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
