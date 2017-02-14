@extends('layout')

@section('content')
{!! Form::open() !!}

<div class="form-group">
{!! Form::label('subtopic', 'Enter new name of sub topic:') !!}
{!! Form::text('subtopic', $subtopic->stopic, ['class'=>'form-control']) !!}
</div>

<div class="form-group">
{!! Form::submit('Update subtopic', ['name'=>'u']) !!}
</div>

<div class="form-group">
{!! Form::submit('Delete', ['name'=>'d']) !!}
</div>

{!! Form::close() !!}
@stop
