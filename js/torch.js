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
