<div class="divartcontent">
  
  <ul class="articleoptions">
    <li class="edit">{{ Html::image('img/write.png','',['width'=>15]) }}</li> 
    <li class="close">{{ Html::image('img/close.png','',['width'=>16]) }}</li>
    <li class="expand" title="Expand">{{ Html::image('img/expand.png','',['width'=>14]) }}</li>
  </ul>

  <div><em>{{ $content->content_id }} {{ \Carbon\Carbon::parse($content->created_at)->format('d/m/Y H:i') }} {{ \Carbon\Carbon::parse($content->updated_at)->format('d/m/Y H:i') }}</em></div>

  <div>{!! $content->content !!}</div>

  <div class="article_btns">
    {!! Form::button('Edit >',['id'=>'editarticle','class'=>'active','title'=>$content->content_id]) !!}
    {!! Form::button('Close >',['id'=>'closeeditarticle']) !!}
  </div>

</div>
<script>
$(document).ready(function(){

  // Edit article - show form when clicking Edit.
  $('#editarticle').off('click').on('click', function() {
    var articleid = $(this).attr('title');
    // Anchor the article title:
    var article = $(this).parent().parent().parent().parent().find('a');
    if (article.attr('class')=='subbarticle') { // Reply.
      article = article.parent().parent().parent().find('a');
    }
    $.ajax({
      "type":"GET",
      "url":"article/edit/" + parseInt(articleid),
      "success":function(data){
        $('#articlediv').html(data).fadeIn('slow');
        //if ($('#helpcontent').val().length==0) {$('#helpcontent').focus();}
        //if ($('#helptitle').val().length==0)  {$('#helptitle').focus();}      
      } // End ajax success function
    });
    $('html, body').animate({scrollTop: article.offset().top}, 0);
  });

  // Click option to expand article.
  $('.divartcontent li.expand').click(function(){
    var parentdiv = $(this).parent().parent().parent(); // #divarticle
    parentdiv.toggleClass('togglewidth');
    if (parentdiv.hasClass('togglewidth')) {
      var subtopic = $(this).parent().parent().parent().parent().parent().parent().prev().prev().find('a').text();
      var article_name=$(this).parent().parent().parent().parent().find('a').first().text();
      if (parentdiv.parent().find('a').attr('class')=='subbarticle') { // Is a reply.
        subtopic = $(this).parent().parent().parent().parent().parent().parent().parent().parent().prev().prev().find('a').text();
        var article = $(this).parent().parent().parent().parent().parent().parent().parent().find('a').first().text();
        var str = subtopic+' &rarr; '+article+' &rarr; '+article_name;
      } else {
        var str = subtopic+' &rarr; '+article_name;
      }
      $('<div class="prepended">'+str+'</div>').prependTo($(this).parent().parent());
      $(this).attr('title','Contract').html('<img src="img/expand2.png">');
      $('.article_btns').hide();
      $('li img[src*=close]').hide();
      $('body, html').css('overflow','hidden');
    } else {
      $(this).attr('title','Expand').html('<img src="img/expand.png" width="16">');
      $('.article_btns').show();
      $('li img[src*=close]').show();
      $('div.prepended').remove();
      $('body, html').css('overflow','auto');
    }
  });

  // Close article in overlay.
  $('.divartcontent li.close').click(function(){
    $('#articlediv').remove();
    $('body, html').css('overflow','auto');
  });

  // Edit article in overlay.
  $('.divartcontent li.edit').click(function(){
    var articleid = $(this).parent().parent().parent().parent().find('a').attr('title2');
    $.ajax({
      "type":"GET",
      "url":"article/edit/" + parseInt(articleid),
      "success":function(data){$('#articlediv').html(data).fadeIn('slow');}
    });
  });


});
</script>