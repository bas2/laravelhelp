<ul id="projectsmenu">
@foreach($projlist as $project)
  @if (\Config::get('constants.appname')==$project)
  <li class="sel">{{ $project }}</li>
  @else
  <li><a href="/{{ strtolower($project) }}/public/home">{{ $project }}</a></li>
  @endif
@endforeach
</ul>