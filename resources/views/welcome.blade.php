@extends('layout')

@section('mainheader')
  {!! Html::style('js/jquery/jquery-ui-1.12.1.custom/jquery-ui.css') !!}

  {!! Html::script('js/jquery/jquery-3.1.1.min.js') !!}
  {!! Html::script('js/jquery/jquery-ui-1.12.1.custom/external/jquery/jquery.js') !!}
  {!! Html::script('js/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.js') !!}

  {!! Html::script('js/tinymce/jscripts/tiny_mce/jquery.tinymce.js') !!}
  {!! Html::script('js/help.js') !!}
@stop

@section('content')
  <h1>Topics: {{ $numtopics=count($topics) }}</h1>
  {{-- {{ link_to('#','Nav',['class'=>'btnNav']) }} --}}
  @include('projectmenu')
  <div class="topicrow">
    @foreach ($topicrows as $topicrow)
    @for ($i=0; $i<=count($topicrows); $i++)
    <div>

      <div>
      <h2 id="topic_{{ $topicrow[$i]->topic_id }}">{{ $topicrow[$i]->topic }}</h2>
      <div>
      @for ($j=1; $j<=$numtopics; $j++)
      @if ($topicrow[$i]->topic==$topics[$j-1]->topic)
      <span class="current">&bull;</span>
      @else
      <span title2="topic_{{ $topics[$j-1]->topic_id }}" title="{{ $topics[$j-1]->topic }}">&bull;</span>
      @endif
      @if ($j % 3 ==0)
      <span class="sep">&nbsp;</span>
      @endif
      @endfor
      </div>

      {{-- <span class="hidetopic">{{ Html::image('img/hide.png','',['width'=>'40']) }}</span> --}}
      </div>
      

      @foreach (App\Subtopic::where('hide',0)->latest('updated_at')->get(['stopic_id','stopic','topicid']) as $stopic)
      @if ($stopic->topicid==$topicrow[$i]->topic_id)
      <div class="subtopic-container">
      {{ link_to("subtopic/{$stopic->stopic_id}",(empty($stopic->stopic)?'&nbsp;':$stopic->stopic),['id'=>"s_{$stopic->stopic_id}",'title'=>'Rename Sub topic']) }}
        <div>
        @foreach (App\Subtopic::getTopicSubtopics($topicrow[$i]->topic_id) as $subtopic)
        @if ($subtopic->stopic==$stopic->stopic)
        <span class="current">&bull;</span>
        @else
        <span title2="s_{{ $subtopic->stopic_id }}" title="{{ $subtopic->stopic }}">&bull;</span>
        @endif
        @endforeach
        </div>
      </div>

      <p>Order by: <span class="oname">Name</span> <span class="omoddate">Mod date</span></p>
      <div>
      @include('ajax.content', ['orderby'=>['updated_at','desc']])
      </div>

      @endif
      @endforeach

      <p>{{ link_to("subtopic/new/{$topicrow[$i]->topic_id}",'New sub topic',['class'=>'newsubtopic']) }}</p>

    </div>
    @if ($i<count($topicrows))
    <div class="sep"></div>
    @endif
    @endfor
  </div>
  <div class="topicrow">
  @endforeach
  </div>
@stop
