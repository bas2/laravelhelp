@extends('layout')

@section('content')
{!! Form::open() !!}

<div class="form-group">
{!! Form::label('subtopic', 'Enter name of new sub topic:') !!}
{!! Form::text('subtopic', '', ['class'=>'form-control']) !!}
</div>

<div class="form-group">
{!! Form::submit('New subtopic', null, ['class'=>'form-control']) !!}
</div>

{!! Form::close() !!}
@stop
