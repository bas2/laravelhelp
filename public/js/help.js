$(document).ready(function() {

  function hideshow() {
    $('.articleoption').hide();
    $('<span class="show" title="Show"><img src="_inc/img/menu.png" width="30"></span>')
     .appendTo('a.mainarticle').addClass('moveright');
  }
  hideshow();

  // Show option
  $('a.mainarticle span.show').live('click', function(e) {
     $('.article' + $(this).parent().parent().parent().parent().prev().prev().attr('id').substr(2) + 'option').toggle(); // .articlexxxoption
     e.preventDefault();
  });


  // Show article: click on a main article or reply to a main article.
  $('.mainarticle, .subbarticle').die('click').live('click', function(e) {
    var articleid = $(this).attr('title2');
    var classsel  = $(this).attr('class'); // Main article or reply?
    var thisarticledivvisible = $(this).parent().find('#articlediv').is(":visible");
    $(this).css('background','rgba(255, 0, 50, .2)');
    $.ajax({
      "type":"POST",
      "url":"_inc/ajax/ajax.hdisplayarticle.php",
      "data": 'id=' + parseInt(articleid),
      "success":function(data){
        if ($('#articlediv').is(":visible")) {$('#articlediv').remove();}
        if (!thisarticledivvisible) {
          $('<div id="articlediv">'+data+'</div>').insertAfter('#'+classsel+articleid).show();
        }
        $('html, body').animate({scrollTop: $('#'+classsel+articleid).offset().top}, 0);
      } // End ajax success function
    });
    e.preventDefault();
  });
  

  // Close
  $('#closeeditarticle').live('click', function() {
    $('html, body').animate({scrollTop: $("#articlediv").parent().find('a').offset().top}, 0);
    $("#articlediv").parent().find('a')
    .css({'background':'blue','transition':'background 4s ease-out'})
    ;
    $('#articlediv').remove();
  });
  //transition: background-color 2s ease-out;

  // Delete
  $('#deletearticle').live('click', function() {
    if ($(this).val()=='Confirm?') {
      $('#li_art'+$(this).attr('title')).remove();
    }
    $(this).val('Confirm?');
  });
  
   
  // Add new reply:
  $('a.reply').die('click').live('click',function(e) {
    var articleid = $(this).attr('title2');
    $.ajax({
      "type":"POST",
      "url":"_inc/ajax/ajax.hreplytoarticle.php",
      "data": 'id=' + parseInt(articleid),
      "success":function(data){
        var splitdata = data.split('|');
        var str = '<li><a id="subbarticle'+splitdata[0]+'" class="subbarticle" href="#" title="View article" title2="'+splitdata[0]+'">View article</a><span id="rposter'+splitdata[0]+'" class="rposter">By ['+splitdata[1]+'] on ['+splitdata[2]+']</span></li>';
        if ($('#replylist'+articleid+' li').length>0) {
          $(str).insertAfter('#replylist'+articleid+' li:last'); // Replies exist already.
        } else {$('#replylist'+articleid).append(str);}

      } // End ajax success function
    });
    e.preventDefault();
  });
   

  // Add new article:
  $('.newarticle').click(function(e) {
    var stopicid = $(this).attr('title2');
    var test     = $(this); // So we can add new article before new article link
    $.ajax({
      "type":"POST",
      "url":"_inc/ajax/ajax.hnewarticle.php",
      "data": 'id=' + parseInt(stopicid),
      "success":function(articleid){
        var splitdata = articleid.split('|');
        $('<a id="mainarticle' + splitdata[0] + '" class="mainarticle" title2="'
        +splitdata[0]+'" href="#">[New article]</a><span class="aposter" id="aposter'
        +splitdata[0]+'">By ['
        +splitdata[1]+'] on ['
        +splitdata[2]+']</span>')
        .insertBefore(test).show();
      } // End ajax success function

    });
    e.preventDefault();
  });


  // Nav button to navigate around
  $('a.btnNav').click(function(e){
    if ($('.divnav').length==0) {
      var $that = $(this);
      $.ajax({
        "type":"POST",
        "url":"_inc/ajax/ajax.nav.php",
        "success":function(data){
          var splitdata = data.split('=||=');
          data = splitdata[0] + splitdata[1];
          $('<div class="divnav">'+data+'</div>').insertAfter($that);
          $('.nav-subtopics').hide();
          if ($('.nav-subtopics').length==0) $('#showsubtopics').attr('checked','checked');
        } // End ajax success function

      }); // End ajax.
    } else {
      $('.divnav').remove();
    } // End if.
    e.preventDefault();
  });

  // 20/08/15 - As we click in our Search box, open up DIV and display
  $('form.searchform input[type=text]').live('click', function() {
      var $that = $(this);
      $.ajax({
        "type":"POST",
        "url":"_inc/ajax/ajax.nav.php",
        "data":"q=" + encodeURIComponent($that.val()),
        "success":function(data){
          var splitdata = data.split('=||=');
          data = splitdata[1];
          $('.divnav div.tbl').html(data);
          // 21/08/15 - shorten text and allow read more ..
          $('.divnav ul.content li span').hide() ;
        } // End ajax success function

      }); // End ajax.

  });
   
  $('.divnav ul.content li').live('click', function() {
    $(this).find('span').toggle();
  });

  // Show suptopics when the checkbox is clicked:
  $('.divnav #showsubtopics').live('click', function() {
    $('.nav-subtopics').toggle();
  });

  // Hide topic link:
  $('table#helpsection h2 span.hidetopic').click(function(){
    var $that = $(this);
    $.ajax({
      "type":"GET",
      "url":"_inc/ajax/ajax.hidetopic.php",
      "data":"topicid=" + $that.attr('title'),

      "success":function(data){
        $that.parent().parent().fadeOut() ;
        alert(data);
      } // End ajax success function

    }); // End ajax.
  });


  // Show hidden topics when the checkbox is clicked:
  $('.divnav #hiddentopics').live('click', function() {
    $('.topichidden').toggle();
  });

  // Pin or unpin content from nav:
  $('#articlediv #pintonav').live('click', function() {
      var $that = $(this);
      $.ajax({
        "type":"GET",
        "url":"_inc/ajax/ajax.pincontent.php",
        "data":"contentid=" + $that.attr('value') + "&checked=" + $that.attr('checked'),

        "success":function(data){
          alert(data);
        } // End ajax success function

      }); // End ajax.
  });

  // Topic 'dots' tooltip.
  $( "table#helpsection h2 div span, table#helpsection h3 div span" ).tooltip({
    tooltipClass: "jquerytooltip",
    position: {
      "my": "center top-54",
      //"at": "center top"
    },
    //open: function( event, ui ) {
    //  ui.tooltip.animate({ top: ui.tooltip.position().top + 5 }, "fast" );
    //},
    show: { effect: "none" },
    hide: { effect: "none" }
  });

  $( "table#helpsection h3 div span" ).tooltip({
    tooltipClass: "jquerytooltip2",
    position: {
      "my": "center top-40",
    },
    show: { effect: "none" },
    hide: { effect: "none" }
  });

  $('table#helpsection h2 div span, table#helpsection h3 div span').click(function(e){
    $('html, body').animate({scrollTop: $('#'+$(this).attr('title2')).offset().top}, 0);
    e.preventDefault();
  });

  $('h3 + p span').click(function(){
    $(this).parent().find('span').css('color','white');
    $(this).css('color','yellow');
    var stopicid = $(this).parent().prev().attr('id').substring(2);
    var $this = $(this);
    $.ajax({
      "type":"GET",
      "url":"_inc/ajax/ajax.subtopicarticles.php?stopicid=" + stopicid,
      "data":"orderby=" + $this.attr('class') ,

      "success":function(data){
        $($this).parent().next().html(data);
        hideshow();
      } // End ajax success function

    }); // End ajax.
  });


}); // End document ready.