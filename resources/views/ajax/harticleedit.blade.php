<div class="divharteditform">
<span class="close">X</span>
{!! Form::open() !!}
<div>
{!! Form::text('helptitle', $content->title, ['placeholder'=>'Title is required']) !!}
</div>
<div>
{!! Form::textarea('helpcontent', $content->content, ['id'=>'helpcontent']) !!}
</div>
<div>
{!! Form::label('groups', 'Add to group:') !!}
{!! Form::select('groups', $groups, $content->groupid, ['id'=>'groups']) !!}
</div>
<div class="groupoptions" title2="{{ $content->stopicid }}"></div>
<div>
{!! Form::label('subtopics', 'Subtopic:') !!}
{!! Form::select('subtopics', $subtopics, $content->stopicid, ['id'=>'subtopics']) !!}
<div>

<div class="article_btns">
<input id="updatearticle" class="active" type="button" title="{{ $content->content_id }}" value="Update &gt;" />
<input id="closeeditarticle" type="button"                          value="Close &gt;">
<input id="deletearticle"    type="button" title="{{ $content->content_id }}" value="Delete &gt;">
</div>

{!! Form::close() !!}

</div>
<script>
$('textarea#helpcontent').tinymce({
  // Location of TinyMCE script
  script_url : 'js/tinymce/jscripts/tiny_mce/tiny_mce.js',

  // General options
  theme   : "advanced",
  plugins : "",

  // Theme options
  theme_advanced_buttons1 : "code,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,|,forecolor,backcolor",

  theme_advanced_toolbar_location   : "top",
  theme_advanced_toolbar_align      : "left",
  theme_advanced_statusbar_location : "bottom",
  theme_advanced_resizing           : true,

  // Example content CSS (should be your site CSS)
  content_css : "css/tinymce.css",

  // Drop lists for link/image/media/template dialogs
  template_external_list_url : "lists/template_list.js",
  external_link_list_url     : "lists/link_list.js",
  external_image_list_url    : "lists/image_list.js",
  media_external_list_url    : "lists/media_list.js",

  // Replace values for the template plugin
  template_replace_values : {
    username : "Some User",
    staffid : "991234"
  }
});
$(document).ready(function(){
  $('.close').css({'cursor':'pointer','position':'absolute','right':'.5em','top':'0'});
  if ($('#groups').val()==0) {
    $('.groupoptions').html('<input class="ngroup" type="text"> <a class="a" href="#">Add group</a>');
  } else {
    $('.groupoptions').html('<a class="r" href="#">Remove group</a> <a class="u" href="#">Update group</a>');
  }
  $('#groups').change(function(){
    var selval = $(this).val();
    if (selval==0) {
      $('.groupoptions').html('<input class="ngroup" type="text"> <a class="a" href="#">Add group</a>');
    } else {
      $('.groupoptions').html('<a class="r" href="#">Remove group</a> <a class="u" href="#">Update group</a>');
    }
  });
  $('.groupoptions .a').die('click').live('click',function(e){
    var stopicid = $(this).parent().attr('title2');
    var group    = $('.ngroup').val();
    var $this = $(this);
    $.ajax({
      "type":"POST",
      "url":"_inc/ajax/ajax.groups.php",
      "data": 'act=a&stopicid=' + parseInt(stopicid) + '&name=' + encodeURIComponent(group),
      "success":function(data){
        //alert(data);
        $this.parent().prev().html(data);
        $('.groupoptions').html('<a class="r" href="#">Remove group</a> <a class="u" href="#">Update group</a>');
      } // End ajax success function
    });
    e.preventDefault();
  });

  $('.groupoptions .r').die('click').live('click',function(e){
    var stopicid = $(this).parent().attr('title2');
    var groupid = $('#groups').val();
    var $this   = $(this);
    $.ajax({
      "type":"POST",
      "url":"_inc/ajax/ajax.groups.php",
      "data": 'act=r&groupid=' + parseInt(groupid) + '&stopicid=' + stopicid,
      "success":function(data){
        //alert(data);
        $this.parent().prev().html(data);
        $('.groupoptions').html('<a class="a" href="#">Add group</a>');
      } // End ajax success function
    });
    e.preventDefault();
  });


  // DUPLICATED FROM HELP.JS!
  function hideshow() {
    $('.articleoption').hide();
    $('<span class="show" title="Show"><img src="img/menu.png" width="30"></span>')
     .appendTo('a.mainarticle').addClass('moveright');
  }

  // Update article
  $('#updatearticle').die('click').live('click', function(e) {
    var articleid = $(this).attr('title');
    $.ajax({
      "type":"POST",
      "url":"ajax/harticleedit/" + parseInt(articleid),
      "data": 
      '&txt_helptitle=' + encodeURIComponent( $('input[name=helptitle]').val() )
      + '&fedit_helpcontent=' + encodeURIComponent($('#helpcontent').val())
      + '&groupid=' + parseInt($('#groups').val())
      + '&stopicid=' + parseInt($('#subtopics').val())
      + '&_token={{ csrf_token() }}'
      + '&submitted=TRUE',
      "success":function(data){
        //alert(data);
        var datasplit = data.split('=||=');
        var subtopic = $("#articlediv").parent().parent().parent().prev().prev();
        if ($("#articlediv").prev().attr('class')=='subbarticle') {
          subtopic = $("#articlediv").parent().parent().parent().parent().parent().prev().prev();
        }
        $('#articlediv').remove(); // Remove form.
        $('#mainarticle'+datasplit[0]).css('background', 'black').html(datasplit[1]+'<span class="show moveright"><img src="img/menu.png" width="30"></span>');
        $('#subbarticle'+datasplit[0]).css('background', 'black').html(datasplit[1]+' (0 days)');
        $('html, body').animate({scrollTop: subtopic.offset().top}, 0);
        $.ajax({
          "type":"GET",
          "url":"ajax/content/" + subtopic.attr('id').substring(2),
          "data":"orderby=omoddate" ,
          "success":function(data) {
            //alert(data);
            subtopic.next().next().html(data);
            $('.articleoption').hide();
            $('<span class="show" title="Show"><img src="img/menu.png" width="30"></span>')
     .appendTo($('#mainarticle'+articleid)).addClass('moveright');
            //hideshow();
          } // End ajax success function
        });

      } // End ajax success function
    });
    e.preventDefault();
  });

  $('.close').click(function(){
    $('#articlediv').remove();
  });


});
</script>