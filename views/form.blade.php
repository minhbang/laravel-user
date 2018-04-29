@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($user, ['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="form-group{{ $errors->has('group_id') ? ' has-error':'' }}">
        {!! Form::label('group_id', __('Group'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::select('group_id', $groups, null, ['class' => 'form-control selectize-tree']) !!}
            @if($errors->has('group_id'))
                <p class="help-block">{{ $errors->first('group_id') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('username') ? ' has-error':'' }}">
        {!! Form::label('username', __('Username'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('username', null, ['class' => 'form-control']) !!}
            @if($errors->has('username'))
                <p class="help-block">{{ $errors->first('username') }}</p>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
        {!! Form::label('password', __('Password'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('password', '', ['class' => 'form-control']) !!}
            @if($errors->has('password'))
                <p class="help-block">{{ $errors->first('password') }}</p>
            @endif
            @if($user->exists)
                <p class="help-block">{{ __('Leave blank if you do not want to change password')}}</p>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
        {!! Form::label('name', __('Fullname'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            @if($errors->has('name'))
                <p class="help-block">{{ $errors->first('name') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
        {!! Form::label('email', __('E-mail'), ['class' => 'col-xs-3 control-label']) !!}
        <div class="col-xs-9">
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
            @if($errors->has('email'))
                <p class="help-block">{{ $errors->first('email') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop