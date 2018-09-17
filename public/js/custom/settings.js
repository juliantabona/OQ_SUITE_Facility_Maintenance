(function($) {
  'use strict';
  $(function() {
    $(".nav-settings").click(function(){
      $("#right-sidebar").toggleClass("open");
    });
    $(".settings-close").click(function(){
      $("#right-sidebar,#theme-settings").removeClass("open");
    });

    $("#settings-trigger").on("click" , function(){
      $("#theme-settings").toggleClass("open");
    });
  });
})(jQuery);
