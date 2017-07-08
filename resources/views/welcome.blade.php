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
<h1>Topics: {{ $numtopics = count($topics) }}</h1>
{{-- {{ link_to('#','Nav',['class'=>'btnNav']) }} --}}

@include('projectmenu')

@if($flash = session('message'))
<div class="alert alert-info">{{ $flash }}</div>
@endif


<div class="container-fluid">
    <div class="row equal">
@if(!count($topics))
        <div class="alert alert-warning text-center">No topics
        {{ link_to('topic/new', 'Add topic') }}
        </div>
@else
    @foreach ($topics as $topicrow)
        <div class="col-md-4 col-sm-6 col-xs-12 topic">

            <div>
                <h2 id="topic_{{ $topicrow->topic_id }}">{{ $topicrow->topic }}</h2>
                <div>
        @for ($j = 1; $j <= $numtopics; $j++)
            @if ($topicrow->topic == $topics[$j-1]->topic)
                    <span class="current">&bull;</span>
            @else
                    <span title2="topic_{{ $topics[$j-1]->topic_id }}" title="{{ $topics[$j-1]->topic }}">&bull;</span>
            @endif

            @if ($j % 3 == 0)
                    <span class="sep">&nbsp;</span>
            @endif
        @endfor
                </div>

        {{-- <span class="hidetopic">{{ Html::image('img/hide.png','',['width'=>'40']) }}</span> --}}
            </div>
    

        @foreach (App\Subtopic::where('hide', 0)->latest('updated_at')->get(['stopic_id', 'stopic', 'topicid']) as $stopic)
            @if ($stopic->topicid == $topicrow->topic_id)
            <div class="subtopic-container">
            {{ link_to("subtopic/{$stopic->stopic_id}", (empty($stopic->stopic) ? '&nbsp;' : $stopic->stopic), ['id' => "s_{$stopic->stopic_id}", 'title' => 'Rename Sub topic']) }}
                <div>
                @foreach (App\Subtopic::getTopicSubtopics($topicrow->topic_id) as $subtopic)
                    @if ($subtopic->stopic == $stopic->stopic)
                    <span class="current">&bull;</span>
                    @else
                    <span title2="s_{{ $subtopic->stopic_id }}" title="{{ $subtopic->stopic }}">&bull;</span>
                    @endif
                @endforeach
                </div>
            </div>

            <p>Order by: <span class="oname">Name</span> <span class="omoddate">Mod date</span></p>
            <div>
            @include('ajax.content', ['orderby' => ['updated_at', 'desc']])
            </div>

            @endif
        @endforeach

            <p>{{ link_to("subtopic/new/{$topicrow->topic_id}", 'New sub topic', ['class'=>'newsubtopic']) }}</p>

        </div>
    @endforeach
@endif
    </div>
</div>
@endsection
