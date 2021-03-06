@extends('kit::backend.layouts.basic')

@section('heading', Session::get('title') ?: __('Login information'))

@section('content')
    {!! Form::open(['class' => 'm-t']) !!}
    <div class="form-group{{ $errors->has('username') ? ' has-error':'' }}">
        {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => __('Username'), 'required' => '']) !!}
        @if($errors->has('username'))
            <p class="help-block">{{ $errors->first('username') }}</p>
        @endif
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('Password'), 'required' => '']) !!}
        @if($errors->has('password'))
            <p class="help-block">{{ $errors->first('password') }}</p>
        @endif
    </div>
    <div class="form-group">
        <div class="checkbox checkbox-warning">
            {!! Form::checkbox('remember', '1', old('remember'),  ['id' => 'remember']) !!}
            <label for="remember">{{ __('Remember me') }}</label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary block full-width m-b">{{ __('Login') }}</button>
    <a href="{{route('password.email')}}">
        <small>{{__('Forgot password?')}}</small>
    </a>
    {!! Form::close() !!}
@stop
