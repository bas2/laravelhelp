<ul>
@foreach (App\Content::getContent($stopic->stopic_id, $orderby) as $content)
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
<p class="articleoption article{{ $stopic->stopic_id }}option newarticle" title2="{{ $stopic->stopic_id }}"><a>New article</a></p>