$(document).ready(function() {

  function hideshow() {
    $('.articleoption').hide();
    $('<span class="show" title="Show"><img src="img/menu.png" width="30"></span>')
     .appendTo('a.mainarticle').addClass('moveright');
  }
  hideshow();

  // Show option - menu icon.
  $('a.mainarticle span.show').live('click', function(e) {
     $('.article' + $(this).parent().parent().parent().parent().prev().prev().attr('id').substr(2) + 'option').toggle(); // .articlexxxoption
     e.preventDefault();
  });


  // Show article: click on a main article or reply to a main article.
  $('.mainarticle, .subbarticle').die('click').live('click', function(e) {
    var articleid = $(this).attr('title2');
    var classsel  = '#' + $(this).attr('class') + articleid; // Main article or reply?
    if ($(this).attr('class')=='subbarticle') {
      // If reply is clicked, focus main article.
      var mainarticleid = $(this).parent().parent().parent().find('a').attr('title2');
      var mainarticle   = '#mainarticle' + mainarticleid;
    } else {var mainarticle = classsel;}

    var thisarticledivvisible = $(this).parent().find('#articlediv').is(":visible");
    var nextelement = $(this).next().attr('id');
    $(this).css('background','rgba(255, 0, 50, .2)');
    $.ajax({
      "type":"get",
      "url":"ajax/" + parseInt(articleid),
      "success":function(data){
        if ($('#articlediv').is(":visible")) {$('#articlediv').remove();}
        if (!thisarticledivvisible||nextelement!='articlediv') {$('<div id="articlediv">'+data+'</div>').insertAfter(classsel).show();}
        $('html, body').animate({scrollTop: $(mainarticle).offset().top}, 500);
      } // End ajax success function
    });
    e.preventDefault();
  });
  

  // Close article view and edit forms.
  $('#closeeditarticle').live('click', function() {
    if ($(this).parent().parent().parent().parent().parent().attr('class')=='divharteditform') {
      var article=$(this).parent().parent().parent().parent().parent().parent().parent().find('a');
      if (article.attr('class')=='subbarticle'){article=article.parent().parent().parent().find('a');}
    } else {
      var article = $(this).parent().parent().parent().parent().find('a');
      if (article.attr('class')=='subbarticle'){article=article.parent().parent().parent().find('a');}
    }
    $('html, body').animate({scrollTop: article.offset().top}, 0);
    $("#articlediv").parent().find('a').first()
    .css({'background':'blue','transition':'background 4s ease-out'})
    ;
    $('#articlediv').remove();
  });
  //transition: background-color 2s ease-out;

  // Delete
  $('#deletearticle').live('click', function() {
    if ($(this).val()=='Confirm?') { // Reply.
    var articleid = $(this).attr('title');
    $.ajax({
      "type":"GET",
      "url":"ajax/hdelarticle/" + parseInt(articleid),
      //"success":function(data){
      //  alert(data);
      //} // End ajax success function
    });
      //$('#li_art'+$(this).attr('title')).remove();
      if ($(this).parent().parent().parent().parent().parent().parent().parent().find('a').attr('id').substr(0,3)=='sub') {
        $(this).parent().parent().parent().parent().parent().parent().parent().delay(2000).remove();
      } else {
        $(this).parent().parent().parent().parent().parent().parent().parent().delay(2000).remove();
      }
      //$('body').append('');
      $('<p style="position:fixed;top:0;left:50%;font-weight:bold;background:yellow;color:black;padding:.5em;">Article was deleted!</p>')
      .appendTo('body')
      .fadeIn().delay(3000).fadeOut();
    }
    $(this).val('Confirm?');
  });
  
   
  // Add new reply:
  $('a.reply').die('click').live('click',function(e) {
    var articleid = $(this).attr('title2');
    $.ajax({
      "type":"GET",
      "url":"ajax/hreplytoarticle/" + parseInt(articleid),
      "success":function(data){
        //alert(data);
        var splitdata = data.split('|');
        var str = '<li><a id="subbarticle'+splitdata[0]+'" class="subbarticle" href="#" title="View article" title2="'
        +splitdata[0]+'">View article</a><span id="rposter'+splitdata[0]
        +'" class="rposter">By ['+splitdata[1]+'] on ['+splitdata[2]+']</span></li>';
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
      "type":"GET",
      "url":"ajax/hnewarticle/" + parseInt(stopicid),
      "success":function(articleid){
        var splitdata = articleid.split('|');
        $('<li class="article_li"><a id="mainarticle' + splitdata[0] + '" class="mainarticle" title2="'
        +splitdata[0]+'" href="#">[New article]</a><span class="aposter" id="aposter'
        +splitdata[0]+'">By ['
        +splitdata[1]+'] on ['
        +splitdata[2]+']</span></li>')
        .appendTo(test.prev()).show();
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
  $('div.topicrow h2 span.hidetopic').click(function(){
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
  $( "div.topicrow h2 div span, div.topicrow h3 div span" ).tooltip({
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

  $( "div.topicrow h3 div span" ).tooltip({
    tooltipClass: "jquerytooltip2",
    position: {
      "my": "center top-50",
    },
    show: { effect: "none" },
    hide: { effect: "none" }
  });

  $('div.topicrow h2 div span, div.topicrow h3 div span').click(function(e){
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
      "url":"ajax/content/" + stopicid,
      "data":"orderby=" + $this.attr('class') ,
      "success":function(data){
        $($this).parent().next().html(data);
        $('.articleoption').hide();
        $('<span class="show" title="Show"><img src="img/menu.png" width="30"></span>')
     .appendTo($($this.parent().next().find('ul li a.mainarticle'))).addClass('moveright');
        //hideshow();
      } // End ajax success function

    }); // End ajax.
  });


}); // End document ready.