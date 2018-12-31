<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP App::Help!</title>
    <!-- Bootstrap -->
  {!! Html::style('css/bootstrap.min.css') !!}
  {!! Html::style('css/styles.css') !!}
@yield('mainheader')

</head>
<body>

@yield('content')

</body>
</html>