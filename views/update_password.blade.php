@extends('backend.layouts.main')

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{trans('user::account.update_password_title')}}</h5>
        </div>
        <div class="ibox-content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ trans('errors.whoops') }}</strong> {{ $errors->has('msg') ? $errors->first('msg') : trans('errors.input') }}
                </div>
            @endif
            {!! Form::open(['class' => 'form-horizontal']) !!}
            <div class="form-group{{ $errors->has('password_now') ? ' has-error':'' }}">
                {!! Form::label('password_now', trans('user::account.password_now'), ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::password('password_now', ['class' => 'form-control']) !!}
                    @if($errors->has('password_now'))
                        <p class="help-block">{{ $errors->first('password_now') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
                {!! Form::label('password', trans('user::account.password_new'), ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                    @if($errors->has('password'))
                        <p class="help-block">{{ $errors->first('password') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error':'' }}">
                {!! Form::label('password_confirmation', trans('user::account.password_confirmation'), ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    @if($errors->has('password_confirmation'))
                        <p class="help-block">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-success" style="margin-right: 15px;">{{ trans('common.save') }}</button>
                    <a href="{{ route('backend.dashboard')}}">{{ trans('common.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
