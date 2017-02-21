@extends('layout')

@section('mainheader')
  <link rel="stylesheet" href="js/jquery/css/blitzer/jquery-ui-1.10.3.custom.min.css">

  <script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>

  <script src="js/goup.js"></script>
  <script src="js/help.js"></script>
@stop

@section('content')
  <h1>Topics: {{ $numtopics }}</h1>
  <a href="#" class="btnNav">Nav</a> <a href="#" class="scrollup">Scroll</a>
  {{ App\ProjectsMenu::display() }}
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

        <span class="hidetopic"><img src="img/hide.png" width="40"></span>
      </h2>

      @foreach ($stopics as $stopic)
      @if ($stopic->topicid==$topicrow[$i]->topic_id)
      <h3 id="s_{{ $stopic->stopic_id }}"><a href="subtopic/{{ $stopic->stopic_id }}" class="stopic_a" title="Rename Sub topic">
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
      @include('ajax.content', ['orderby'=>['updated_at','desc']])
      </div>

      @endif
      @endforeach

      <p><a href="stopic/new/{{ $topicrow[$i]->topic_id }}" class="newsubtopic">New sub topic</a></p>

    </div>
    @if ($i<$numtopicrows)
    <div class="sep"></div>
    @endif
    @endfor
  </div>
  <div class="topicrow">
  @endforeach
  </div>
@stop
