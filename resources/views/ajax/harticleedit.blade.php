<div class="divharteditform panel">
<span class="close">{{ Html::image('img/close.png','',['width'=>16]) }}</span>
{!! Form::open() !!}
<div>
{!! Form::text('helptitle', $content->title, ['class'=>'form-control','placeholder'=>'Title is required']) !!}
</div>
<div>
{!! Form::textarea('helpcontent', $content->content, ['id'=>'helpcontent']) !!}
</div>
<div>
{!! Form::label('groups', 'Add to group:') !!}
{!! Form::select('groups', $groups, $content->groupid, ['class'=>'form-control','id'=>'groups']) !!}
</div>
<div class="groupoptions" title2="{{ $content->stopicid }}"></div>
<div>
{!! Form::label('subtopics', 'Subtopic:') !!}
{!! Form::select('subtopics', $subtopics, $content->stopicid, ['class'=>'form-control','id'=>'subtopics']) !!}
<div>

<div class="article_btns">
{!! Form::button('Update >',['class'=>'btn btn-primary','id'=>'updatearticle','title'=>$content->content_id]) !!}
{!! Form::button('Cancel >',['id'=>'closeeditarticle','class'=>'btn btn-default']) !!}
{!! Form::button('Delete >',['id'=>'deletearticle','class'=>'btn btn-default','title'=>$content->content_id]) !!}
</div>
{!! Form::close() !!}

</div>
<script>
$('input[name=helptitle]').focus();
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
  plugins : 'autoresize',
  width: '100%',

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
  $('.close').css({'cursor':'pointer','position':'absolute','right':'.1em','top':'0'});

  if ($('#groups').val()==0) {
    $('.groupoptions').html('<input class="ngroup form-control" type="text"> <a class="a" href="#">Add group</a>');
  } else {
    $('.groupoptions').html('<a class="r" href="#">Remove group</a>');
  }
  $('body').on('change','#groups',function(){
    var selval = $(this).val();
    if (selval==0) {
      $('.groupoptions').html('<input class="ngroup form-control" type="text"> <a class="a" href="#">Add group</a>');
    } else {
      $('.groupoptions').html('<a class="r" href="#">Remove group</a>');
    }
  });
  // Add group.
  $(document).off('click').on('click','.groupoptions .a',function(e){
    var stopicid = $(this).parent().attr('title2');
    var group    = $('.ngroup').val();
    var $this = $(this);
    $.ajax({
      "type":"POST",
      "url":"group/add/" + parseInt(stopicid),
      "data": 'name=' + encodeURIComponent(group) + '&_token={{ csrf_token() }}',
      "success":function(data){
        $this.parent().prev().html(data);
        $('.groupoptions').html('<a class="r" href="#">Remove group</a> <a class="u" href="#">Update group</a>');
      } // End ajax success function
    });
    e.preventDefault();
  });
  // Remove group.
  $('.groupoptions').off('click').on('click','.r',function(e){
    var stopicid = $(this).parent().attr('title2');
    var groupid = $('#groups').val();
    var $this   = $(this);
    $.ajax({
      "type":"POST",
      "url":"group/remove/" + parseInt(groupid),
      "data": 'stopicid=' + stopicid + '&_token={{ csrf_token() }}',
      "success":function(data){
        $this.parent().prev().html(data);
        $('.groupoptions').html('<input class="ngroup form-control" type="text"> <a class="a" href="#">Add group</a>');
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
  $('#updatearticle').off('click').on('click', function(e) {
    var articleid = $(this).attr('title');
    var group = parseInt($('#groups').val());
    $.ajax({
      "type":"POST",
      "url":"article/edit/" + parseInt(articleid),
      "data": 
      '&txt_helptitle=' + encodeURIComponent( $('input[name=helptitle]').val() )
      + '&fedit_helpcontent=' + encodeURIComponent($('#helpcontent').val())
      + '&groupid=' + group
      + '&stopicid=' + parseInt($('#subtopics').val())
      ,
      "success":function(data){
        var datasplit = data.split('=||=');
        var subtopic = $("#articlediv").parent().parent().parent().prev().prev().prev().find('a');
        var $order = $("#articlediv").parent().parent().parent().prev().find('span.hilite').attr('class');
        if ($("#articlediv").prev().attr('class')=='subbarticle') {
          subtopic = $("#articlediv").parent().parent().parent().parent().parent().prev().prev().prev().find('a');
        }
        $('#articlediv').remove(); // Remove form.
        $('#mainarticle'+datasplit[0]).css('background', 'black').html(datasplit[1]+'<span class="show moveright"><img src="img/menu.png" width="30"></span>');
        $('#subbarticle'+datasplit[0]).css('background', 'black').html(datasplit[1]+' (0 days)');
        $('html, body').animate({scrollTop: subtopic.offset().top}, 0).css('overflow','auto');
        $.ajax({
          "type":"GET",
          "url":"article/subtopic/" + subtopic.attr('id').substring(2),
          "data":"orderby=" + $order.substring($order, $order.indexOf(' ')) + 
          "&group=" + group,
          "success":function(data) {
            subtopic.parent().next().next().next().html(data);
            $('.hide2').hide();
            $('<span class="show" title="Show"><img src="img/menu2.png" width="30"></span>')
     .appendTo($('#mainarticle'+articleid)).addClass('moveright');
          } // End ajax success function
        });

      } // End ajax success function
    });
    e.preventDefault();
  });

  $('.close').click(function(){$('#articlediv').remove();});

});
</script>