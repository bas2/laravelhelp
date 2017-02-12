$(document).ready(function() {
  $('.scrollup').hide();

  $(window).scroll(function(){
    if ($(this).scrollTop() > 100) {$('.scrollup').fadeIn();} 
    else {$('.scrollup').fadeOut();}
  });
   
  $('.scrollup').click(function(e){
    $("html, body").animate({ scrollTop: 0 }, 500);
    e.preventDefault();
  });

});
