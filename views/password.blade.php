@extends('kit::backend.layouts.basic')
@section('heading', __('Password recovery'))
@section('content')
    {!! Form::open(['class' => 'm-t']) !!}
    <div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
        {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => __('E-mail of the account'), 'required' => '']) !!}
    </div>
    <button type="submit"
            class="btn btn-primary block full-width m-b">{{ __('Send password recovery link') }}</button>
    {!! Form::close() !!}
@endsection