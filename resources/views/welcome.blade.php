<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <title>PHP App::Help!</title>

  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="js/jquery/css/blitzer/jquery-ui-1.10.3.custom.min.css">

  <script src="js/jquery/js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery/js/jquery-ui-1.10.3.custom.min.js"></script>
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
<span title2="topic_{{ $j }}" title="{{ $topics[$j-1]->topic }}">&bull;</span>
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
<h3><a href="/subtopic/rename" class="stopic_a" title="Rename Sub topic"><div>{{ $stopic->stopic }}</div>
<div>
@for ($j=1; $j<=$numtopics; $j++)
@if ($topicrow[$i]->topic==$topics[$j-1]->topic)
<span class="current">&bull;</span>
@else
<span title2="subtopicc_{{ $j }}" title="{{ $topics[$j-1]->topic }}">&bull;</span>
@endif
@endfor
</div>
</a></h3>
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
