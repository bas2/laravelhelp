<div class="divartcontent">

  <div><em>{{ $content->content_id }} / {{ \Carbon\Carbon::parse($content->created_at)->format('d/m/Y H:i') }} / {{ \Carbon\Carbon::parse($content->updated_at)->format('d/m/Y H:i') }}</em><span class="edit">E</span> <span class="close">X</span> <span class="expand" title="Expand">&uarr;</span></div>
  <div>{!! $content->content !!}</div>

  <div class="article_btns">
  <input id="editarticle" class="active" title="{{ $content->content_id }}" type="button" value="Edit &gt;">
  <input id="closeeditarticle"                          type="button" value="Close &gt;">
  </div>

</div>
<script>
$(document).ready(function(){

  // Edit article - show form when clicking Edit.
  $('#editarticle').die('click').live('click', function() {
    var articleid = $(this).attr('title');
    // Anchor the article title:
    var article = $(this).parent().parent().parent().parent().find('a');
    if (article.attr('class')=='subbarticle') { // Reply.
      article = article.parent().parent().parent().find('a');
    }
    $.ajax({
      "type":"GET",
      "url":"ajax/harticleedit/" + parseInt(articleid),
      //"data": 'id=' + parseInt(articleid),
      "success":function(data){
        $('#articlediv').html(data).fadeIn('slow');
        //if ($('#helpcontent').val().length==0) {$('#helpcontent').focus();}
        //if ($('#helptitle').val().length==0)  {$('#helptitle').focus();}      
      } // End ajax success function
    });
    $('html, body').animate({scrollTop: article.offset().top}, 0);
  });

  // Click option to expand article.
  $('.divartcontent span.expand').click(function(){
    var parentdiv = $(this).parent().parent().parent(); // #divarticle
    parentdiv.toggleClass('togglewidth');
    if (parentdiv.hasClass('togglewidth')) {
      var subtopic = $(this).parent().parent().parent().parent().parent().parent().prev().prev().find('div').first().text();
      var article_name = $(this).parent().parent().parent().parent().find('a').first().text();
      $('<div class="prepended">'+subtopic+' &rarr; '+article_name+'</div>').prependTo($(this).parent().parent());
      $(this).attr('title','Contract').html('&darr;');
      $('.article_btns').hide();
      $('body, html').css('overflow','hidden');
    } else {
      $(this).attr('title','Expand').html('&uarr;');
      $('.article_btns').show();
      $('div.prepended').remove();
      $('body, html').css('overflow','auto');
    }
  });

  $('.divartcontent span.close').click(function(){
    $('#articlediv').remove();
  });

  $('.divartcontent span.edit').click(function(){
    var articleid = $(this).parent().parent().parent().parent().find('a').attr('title2');
    $.ajax({
      "type":"GET",
      "url":"ajax/harticleedit/" + parseInt(articleid),
      "success":function(data){$('#articlediv').html(data).fadeIn('slow');}
    });
  });




});
</script>