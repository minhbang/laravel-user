@extends('backend.layouts.blank')

@section('content')
    <div class="middle-box text-center loginscreen">
        <div>
            <div class="logo">
                <div class="app-name"> {{setting('app.name_short') }}</div>
            </div>
            <h3>{{trans('user::account.reset_password')}}</h3>
            @if (session('status'))
                <div class="alert alert-success">
                    {!! session('status') !!}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ trans('errors.whoops') }}</strong><br>{!! $errors->first('email') !!}
                </div>
            @endif
            {!! Form::open(['class' => 'm-t']) !!}
            <div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
                {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('user::account.email_reset_password'), 'required' => '']) !!}
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">{{ trans('user::account.send_password_reset_link') }}</button>
            {!! Form::close() !!}
            <p class="m-t">
                <small>{{setting('app.name_long') }} &copy; 2015</small>
            </p>

        </div>
    </div>
@endsection