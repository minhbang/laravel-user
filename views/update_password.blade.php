@extends('kit::backend.layouts.basic')

@section('heading', __('Change password'))

@section('classes', 'col-md-6 col-md-offset-3')

@section('content')
    {!! Form::open(['class' => 'form-horizontal']) !!}
    <div class="hr-line-solid"></div>
    <div class="form-group{{ $errors->has('password_now') ? ' has-error':'' }}">
        {!! Form::label('password_now', __('Old password'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::password('password_now', ['class' => 'form-control']) !!}
            @if($errors->has('password_now'))
                <p class="help-block">{{ $errors->first('password_now') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
        {!! Form::label('password', __('New password'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::password('password', ['class' => 'form-control']) !!}
            @if($errors->has('password'))
                <p class="help-block">{{ $errors->first('password') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error':'' }}">
        {!! Form::label('password_confirmation', __('Password confirmation'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
            @if($errors->has('password_confirmation'))
                <p class="help-block">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>
    </div>
    <div class="hr-line-solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-success"
                    style="margin-right: 15px;">{{ __('Save') }}</button>
            <a href="{{ URL::previous()}}">{{ __('Cancel') }}</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
