@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($user, ['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group{{ $errors->has('group_id') ? ' has-error':'' }}">
        {!! Form::label('group_id', trans('user::user.group_id'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::select('group_id', $groups, null, ['class' => 'form-control selectize-tree']) !!}
            @if($errors->has('group_id'))
                <p class="help-block">{{ $errors->first('group_id') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('username') ? ' has-error':'' }}">
        {!! Form::label('username', trans('user::user.username'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('username', null, ['class' => 'form-control']) !!}
            @if($errors->has('username'))
                <p class="help-block">{{ $errors->first('username') }}</p>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
        {!! Form::label('password', trans('user::user.password'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('password', '', ['class' => 'form-control']) !!}
            @if($errors->has('password'))
                <p class="help-block">{{ $errors->first('password') }}</p>
            @endif
            @if($user->exists)
                <p class="help-block">{{ trans('user::user.update_password_hint')}}</p>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
        {!! Form::label('name', trans('user::user.name'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            @if($errors->has('name'))
                <p class="help-block">{{ $errors->first('name') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
        {!! Form::label('email', trans('user::user.email'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
            @if($errors->has('email'))
                <p class="help-block">{{ $errors->first('email') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop