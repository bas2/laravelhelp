<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <title>PHP App::Help!</title>

  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="js/jquery/css/blitzer/jquery-ui-1.10.3.custom.min.css">

  <script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>

  <script src="js/goup.js"></script>
  <script src="js/help.js"></script>
</head>
<body>
  <h1>Topics: {{ $numtopics }}</h1>
  <a href="#" class="btnNav">Nav</a> <a href="#" class="scrollup">Scroll</a>

  <div class="topicrow">
    @foreach ($topicrows as $topicrow)
    @for ($i=0; $i<=$numtopicrows; $i++)
    <div>

      <h2 id="topic_{{ $topicrow[$i]->topic_id }}">{{ $topicrow[$i]->topic }}
        <div>
        @for ($j=1; $j<=$numtopics; $j++)
        @if ($topicrow[$i]->topic==$topics[$j-1]->topic)
        <span class="current">&bull;</span>
        @else
        <span title2="topic_{{ $topics[$j-1]->topic_id }}" title="{{ $topics[$j-1]->topic }}">&bull;</span>
        @endif
        @if ($j % 3 ==0)
        <span class="sep">&VerticalSeparator;</span>
        @endif
        @endfor
        </div>

        <span class="hidetopic"><img src="../img/hide.png" width="40"></span>
      </h2>

      @foreach ($stopics as $stopic)
      @if ($stopic->topicid==$topicrow[$i]->topic_id)
      <h3 id="s_{{ $stopic->stopic_id }}"><a href="/subtopic/rename" class="stopic_a" title="Rename Sub topic">
      <div>{{ $stopic->stopic }}</div>
        <div>
        <?php $subtopics=App\Subtopic::getTopicSubtopics($topicrow[$i]->topic_id); ?>
        @foreach ($subtopics as $subtopic)
        @if ($subtopic->stopic==$stopic->stopic)
        <span class="current">&bull;</span>
        @else
        <span title2="s_{{ $subtopic->stopic_id }}" title="{{ $subtopic->stopic }}">&bull;</span>
        @endif
        @endforeach
        </div></a>
      </h3>

      <p>Order by: <span class="oname">Name</span> <span class="omoddate">Mod date</span></p>
      <div>
      <ul>
      @foreach (App\Content::getContent($stopic->stopic_id) as $content)
        <li class="article_li"><a id="mainarticle{{ $content->content_id }}" class="mainarticle" title2="{{ $content->content_id }}">
        @if (!empty($content->name.$content->title))
        {{ $content->name }} {{ $content->title }}
        @else
        &nbsp;
        @endif
        </a>
        <ul id="replylist{{ $content->content_id }}">
        @foreach (App\Content::getReplies($content->content_id) as $reply)
        <li><a id="subbarticle{{ $reply->content_id }}" class="subbarticle" title2="{{ $reply->content_id }}">
        @if(!empty($reply->title))
        {{ $reply->title }}
        @else
        &nbsp;
        @endif
        </a></li>
        @endforeach
        </ul>
        <p class="articleoption article{{ $stopic->stopic_id }}option"><a id="reply{{ $content->content_id }}" class="reply" title2="{{ $content->content_id }}">Reply</a></p>
        </li>
      @endforeach
      </ul>
      </div>

      @endif
      @endforeach

    </div>
    @endfor
  </div>
  <div class="topicrow">
  @endforeach
  </div>

</body>
</html>
