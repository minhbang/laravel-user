@extends('kit::backend.layouts.basic')
@section('heading', __('Password recovery'))
@section('content')
    {!! Form::open(['class' => 'm-t']) !!}
    {!! Form::hidden('token', $token) !!}
    <div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
        {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => __('E-mail of the account'), 'required' => '']) !!}
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('New password'), 'required' => '']) !!}
    </div>
    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error':'' }}">
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => __('Password confirmation'), 'required' => '']) !!}
    </div>
    <button type="submit" class="btn btn-primary block full-width m-b">{{ __('Save') }}</button>
    {!! Form::close() !!}
@endsection
