@extends('layout')

@section('content')
{!! Form::open() !!}

<div class="form-group">
{!! Form::label('topic', 'Enter name of new topic:') !!}
{!! Form::text('topic', '', ['class'=>'form-control']) !!}
</div>

<div class="form-group">
{!! Form::submit('New topic', ['class'=>'form-control btn btn-primary']) !!}
</div>

{!! Form::close() !!}
@stop
